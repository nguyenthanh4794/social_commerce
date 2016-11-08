<?php

class Socialcommerce_Payment
{
    /**
     * Order actions
     */
    const ACTION_INIT   = 'init';
    const ACTION_AUTH   = 'auth';
    const ACTION_SALE   = 'sale';

    /**
     * Transaction actions
     */
    const ACTION_CAPTURE    = 'capture';
    const ACTION_VOID       = 'void';
    const ACTION_REFUND     = 'refund';
    const ACTION_STATUS     = 'status';

    /**
     * PAYPAL DEFAULT ID
     */
    const PAYPAL_ID = 2;

    /**
     * Allowed order action
     */
    protected $_orderActions = array(
        self::ACTION_INIT,
        self::ACTION_AUTH,
        self::ACTION_SALE,
    );

    /**
     * Allowed transaction actions
     */
    protected $_transactionActions = array(
        self::ACTION_CAPTURE,
        self::ACTION_VOID,
        self::ACTION_REFUND,
        self::ACTION_STATUS
    );

    /**
     * @var Socialcommerce_Payment_Options
     */
    protected $_options;

    /**
     * @var Socialcommerce_Payment_Gateway_Interface
     */
    protected $_gateway;
	
	protected $_paytype;

	public function getSandboxMode(){
		return true;
	}

    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    public function setOptions(array $options)
    {
        $this->_options = new Socialcommerce_Payment_Options($options);
        if ($this->_options->has('gateway')) {
            $this->_gateway = self::factory(
                $this->_options->get('gateway'),
                $this->_options->get('gateway_config'),
                $this->_options->get('gateway_is_custom'),
                $this->getSandboxMode()
            );
        }
        return $this;
    }

    /**
     * Factory to construct payment gateway instance
     *
     * @param string            $code gateway code or class name
     * @param array|Zend_Config $config gateway credentials
     * @param boolean           $custom flag that determine custom class name usage
     * @throws Socialcommerce_Payment_Exception
     * @return Socialcommerce_Payment_Gateway_Abstract
     */
    static public function factory($code, $config=null, $custom=false, $sandboxMode =  null)
    {
        if ($custom) {
            $class = $code;
        } else {
            $class = 'Socialcommerce_Payment_Gateway_'.str_replace(' ', '', ucwords(str_replace('_', ' ', $code)));
        }

		if($config == null){
            $gatewayItem = Engine_Api::_()->getDbtable('gateways', 'payment')->find(self::PAYPAL_ID)->current();
            $config = array();
            $config['user'] = $gatewayItem->config['username'];
            $config['password'] = $gatewayItem->config['password'];
            $config['signature'] = $gatewayItem->config['signature'];
            $config['enable'] = 1;
		}

		if($sandboxMode === NULL){
			$sandboxMode =  Socialcommerce_Api_Core::isSandboxMode();
		}
		if ($config == null) {
			return false;
		}

        return new $class($config, $sandboxMode);
    }

    /**
     * Gateway setter
     *
     * @param Socialcommerce_Payment_Gateway_Interface $gateway
     * @return Socialcommerce_Payment
     */
    public function setGateway(Socialcommerce_Payment_Gateway_Interface $gateway)
    {
        $this->_gateway = $gateway;
        return $this;
    }

    /**
     * Gateway getter
     *
     * @throws Socialcommerce_Payment_Exception
     * @return Socialcommerce_Payment_Gateway_Abstract
     */
    public function getGateway()
    {
        if (!$this->_gateway) {
            throw new Socialcommerce_Payment_Exception('Payment gateway is not defined');
        }
        return $this->_gateway;
    }

    /**
     * Get payment request options
     *
     * @param string $action
     * @return Socialcommerce_Payment_Request_Interface
     */
    public function getRequest($action)
    {
        if ($this->_options->has('request_class')) {
            $class = $this->_options->get('request_class');
        } else {
            $class = 'Socialcommerce_Payment_Request';
        }
        Zend_Loader::loadClass($class);
        $request = new $class($action);

        return $request;
    }

    /**
     * Process payment request
     *
     * @param $request
     * @return Socialcommerce_Payment_Response
     */
    public function process(Socialcommerce_Payment_Request_Interface $request)
    {
        return $this->_gateway->process($request);
    }

    /**
     * Process action on order
     *
     * @param Socialcommerce_Payment_Order       $order
     * @param Socialcommerce_Payment_Method_Interface $method
     * @param string                           $action
     * @throws Socialcommerce_Payment_Exception
     * @return Socialcommerce_Payment_Response
     */
    public function processOrder($order, $method, $action)
    {
        if (!in_array($action, $this->_orderActions)) {
            throw new Socialcommerce_Payment_Exception('Not supported order action: '.$action);
        }
        $request = $this->getRequest($action);
        $request->setOrder($order);
        $request->setMethod($method);
        return $this->_gateway->process($request);
    }

    /**
     * Process action on previous transaction
     *
     * @param $transaction
     * @param $action
     * @throws Socialcommerce_Payment_Exception
     * @return Socialcommerce_Payment_Response
     */
    public function processTransaction($transaction, $action)
    {
        if (!in_array($action, $this->_transactionActions)) {
            throw new Exception('Not supported transaction action: '.$action);
        }

        $request = $this->getRequest($action);
        $request->setTransaction($transaction);
        return $this->_gateway->process($request);
    }
	
	
}
