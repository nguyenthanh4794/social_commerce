<?php

class Socialcommerce_Payment_Gateway_Abstract implements Socialcommerce_Payment_Gateway_Interface {
/**
     * Merchant credentials
     *
     * @var array
     */
    protected $_credentials;

    /**
     * Required credentials keys
     *
     * @var array
     */
    protected $_credentialKeys = array();

    /**
     * @var Socialcommerce_Payment_Request_Requirements
     */
    protected $_requirements;

    /**
     * Set of gateway query parameters
     *
     * @var array
     */
    protected $_queryParams;

    /**
     * HTTP client for communication with gateway
     *
     * @var Zend_Http_Client
     */
    protected $_httpClient;

    /**
     * @var Zend_Log
     */
    protected $_logger;
	
	/**
     * Sandbox mode flag
     *
     * @var boolean
     */
    protected $_sandboxMode = false;
	
	protected function _getLogFile(){
		$filename = sprintf('%s.%s.log', str_replace('_','-',strtolower(get_class($this))), date('Y-m-d'));  
		return APPLICATION_PATH . '/temporary/log/'. $filename;
	}
	
	
	public function __construct($credenticals, $sandboxMode = true){

		$this->setCredentials($credenticals);
		$sandboxMode = Engine_Api::_()->getApi('settings', 'core')->getSetting('store.mode', '1');
		$this->_sandboxMode = $sandboxMode;	
		$this->_requirements = $this->_getRequirements();
		$writer =   new Zend_Log_Writer_Stream($this->_getLogFile());
		$this->setLogger(new Zend_Log($writer));
	}
	
	/**
     * Merchant credentials setter
     *
     * @param array $credentials
     * @return boolean
     */
    public function setCredentials(array $credentials)
    {
        $data = array();
        foreach ($this->_credentialKeys as $inKey => $key) {
            if (isset($credentials[$inKey])) {
                $data[$key] = $credentials[$inKey];
            } else {
                return false;
            }
        }
        $this->_credentials = $data;
        return true;
    }
    
    public function getCredentials() {
    	return $credenticals;
    }

    /**
     * Prepare initial set of request query parameters
     *
     * @param Socialcommerce_Payment_Request       $request
     * @param Socialcommerce_Payment_Merchant $merchant
     * @return Socialcommerce_Payment_Gateway_Abstract
     */
    protected function _initQuery($request)
    {
        $this->_queryParams = $this->_credentials;
        $this->_queryParams = array_merge($this->_queryParams, $request->getAdvancedOptions());
        return $this;
    }

    /**
     * Prepare list of requirements
     *
     * @return Socialcommerce_Payment_Request_Requirements
     */
    protected function _getRequirements()
    {
        $requirements = new Socialcommerce_Payment_Request_Requirements();
        $requirements->setOnOrder(
            array(Socialcommerce_Payment::ACTION_AUTH, Socialcommerce_Payment::ACTION_SALE, Socialcommerce_Payment::ACTION_INIT),
            true
        );
        $requirements->setOnTransaction(
            array( Socialcommerce_Payment::ACTION_CAPTURE, Socialcommerce_Payment::ACTION_REFUND, Socialcommerce_Payment::ACTION_VOID, Socialcommerce_Payment::ACTION_STATUS,
            ),
            true
        );
        return $requirements;
    }

    /**
     * Check action availability
     *
     * @param string $action
     * @return boolean
     */
    public function isActionAvailable($action)
    {
        return method_exists($this, $this->_getMethodByAction($action));
    }

    /**
     * Get method name by request action
     *
     * @param string $action
     * @return string
     */
    protected function _getMethodByAction($action)
    {
        return '_process'.str_replace(' ', '', ucwords(str_replace('_', ' ', $action)));
    }

    /**
     * Get requirements array associated with request action
     *
     * @param string $action
     * @throws Socialcommerce_Payment_Exception
     * @return array
     */
    public function getActionRequirements($action)
    {
        if (!$this->isActionAvailable($action)) {
            throw new Socialcommerce_Payment_Exception('Action "'.$action . '" is not supported');
        }
        return $this->_requirements->get($action);
    }

    /**
     * Perform action processing on gateway
     *
     * @param Socialcommerce_Payment_Request $request
     * @throws Socialcommerce_Payment_Exception
     * @return Socialcommerce_Payment_Response
     */
    public function process(Socialcommerce_Payment_Request_Interface $request)
    {
        if (!$this->_credentials) {
            throw new Socialcommerce_Payment_Exception('Merchant credentials are not defined');
        }

        $action = $request->getAction();
        if (!$this->isActionAvailable($action)) {
            throw new Socialcommerce_Payment_Exception('Action "'.$action . '" is not supported');
        }
        $validationResults = $this->_requirements->validate($request);
		
        if ($validationResults === true) {
            $this->_initQuery($request);
            $method     = $this->_getMethodByAction($action);
            $response   = $this->$method($request);
        } else {
            $response   = new Socialcommerce_Payment_Response(Socialcommerce_Payment_Response::STATUS_ERROR);
            $response->setMessages($validationResults);
        }
        return $response;
    }

    /**
     * Send request to gateway and prepare response
     *
     * @param array $responseMap response fields map array($fromKey => $toKey)
     * @return Socialcommerce_Payment_Response
     */
    protected function _sendRequest($responseMap = array())
    {
        $response   = $this->_send($this->getUrl(), 'POST');
        $result     = $this->_prepareResponse($response, $responseMap);
        return $result;
    }

    /**
     * Send query to gateway
     *
     * @param string $uri
     * @param string $method
     * @throws Zend_Http_Client_Exception
     * @return Zend_Http_Response
     */
    protected function _send($uri, $method='POST')
    {
        $client = $this->getHttpClient();
        $client->setUri($uri);
        $client->setMethod($method);
        $this->_setRequestParams($client);
        try {
            $response = $client->request();
        } catch (Exception $e) {
            $this->_log($e->getMessage(), Zend_Log::ERR);
            throw $e;
        }
        
        $this->_log(
            array(
                'request'   => $client->getLastRequest(),
                'response'  => $client->getLastResponse()->asString(),
            )
        );
        return $response;
    }

    /**
     * Set query parameters to request object.
     *
     * Some payment gateways can't work with encoded data.
     * Zend_Http_Client::setRawData can be used in this case
     *
     * @param Zend_Http_Client $httpClient
     * @return Socialcommerce_Payment_Gateway_Abstract
     */
    protected function _setRequestParams($httpClient)
    {
        $httpClient->setParameterPost($this->_queryParams);
        return $this;
    }

    /**
     * HTTP client getter
     *
     * @return Zend_Http_Client
     */
    public function getHttpClient()
    {
        if (!$this->_httpClient) {
            $this->_httpClient = new Zend_Http_Client(null, array(
                'maxredirects'  => 0,
                'timeout'       => 30,
            ));
        }
        return $this->_httpClient;
    }

    /**
     * Http client setter
     *
     * @param Zend_Http_Client $client
     * @return Socialcommerce_Payment_Gateway_Abstract
     */
    public function setHttpClient(Zend_Http_Client $client)
    {
        $this->_httpClient = $client;
        return $this;
    }

    /**
     * Define logger
     *
     * @param Zend_Log $logger
     * @return Socialcommerce_Payment_Gateway_Abstract
     */
    public function setLogger(Zend_Log $logger)
    {
        $this->_logger = $logger;
        return $this;
    }

    /**
     * Write message
     *
     * @param string  $message
     * @param integer $priority default priority is "DEBUG"
     * @return Socialcommerce_Payment_Gateway_Abstract
     */
    protected function _log($message, $priority = Zend_Log::DEBUG)
    {
        if ($this->_logger) {
            $message = print_r($message, true);
            $this->_logger->log($message, $priority);
        }
        return $this;
    }

    /**
     * Sandbox mode setter
     *
     * @param boolean $flag
     * @return Socialcommerce_Payment_Gateway_Abstract
     */
    public function setSandboxMode($flag)
    {
        $this->_sandboxMode = $flag;
        return $this;
    }

    /**
     * Check sandbox mode flag
     *
     * @return boolean
     */
    public function isSandboxMode()
    {
        return $this->_sandboxMode;
    }
}
