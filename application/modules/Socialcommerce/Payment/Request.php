<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/8/2016
 * Time: 11:49 PM
 */
class Socialcommerce_Payment_Request implements Socialcommerce_Payment_Request_Interface
{
    /**
     * @var string
     */
    protected $_action;

    /**
     * @var Socialcommerce_Payment_Method_Interface
     */
    protected $_method;

    /**
     * @var Socialcommerce_Payment_Transaction
     */
    protected $_transaction;

    /**
     * @var array of Socialcommerce_Payment_Order_Interface
     */
    protected $_order = array();

    /**
     * @var Socialcommerce_Payment_Options
     */
    protected $_options;

    /**
     * @var array
     */
    protected $_advancedOptions = array();

    /**
     * Request constructor
     *
     * @param string $transaction payment transaction
     */
    public function __construct($action)
    {
        $this->setAction($action);
    }

    /**
     * Action setter
     *
     * @param string $action request action
     * @throws Socialcommerce_Payment_Exception if request type isn't string
     * @return Socialcommerce_Payment_Request
     */
    public function setAction($action)
    {
        if (!is_string($action)) {
            throw new Socialcommerce_Payment_Exception('Request type must be as string');
        }
        $this->_action = $action;
        return $this;
    }

    /**
     * Action getter
     *
     * @return string
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * Payment Method getter
     * Usually Credit Card but other payment methods can be supported on gateway level
     *
     * @return Socialcommerce_Payment_Method_Card
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Payment Method setter
     *
     * @param Socialcommerce_Payment_Method_Card $method payment method
     * @return Socialcommerce_Payment_Request
     */
    public function setMethod($method)
    {
        $this->_method = $method;
        return $this;
    }

    /**
     * Options object getter
     *
     * @return Socialcommerce_Payment_Options | null
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Options object setter
     *
     * @param Socialcommerce_Payment_Options $options request options object
     * @return Socialcommerce_Payment_Request
     */
    public function setOptions($options)
    {
        if (is_array($options)) {
            $options = new Socialcommerce_Payment_Options($options);
        }
        $this->_options = $options;
        return $this;
    }

    /**
     * Option value getter
     *
     * @param string $code
     * @return mixed
     */
    public function getOption($code)
    {
        if ($this->_options && $this->_options->has($code)) {
            return $this->_options->get($code);
        }
        return null;
    }

    /**
     * Orders getter
     *
     * @return Socialcommerce_Payment_Order_Interface | null
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Order setter
     *
     * @param Socialcommerce_Payment_Order_Interface $order
     * @return Socialcommerce_Payment_Request
     */
    public function setOrder(Socialcommerce_Model_Order $order)
    {
        $this->_order = $order;
        return $this;
    }

    /**
     * Transactions getter
     *
     * @return Socialcommerce_Payment_Transaction
     */
    public function getTransaction()
    {
        return $this->_transaction;
    }

    /**
     * Transaction setter
     *
     * @param Socialcommerce_Payment_Transaction $transaction
     * @return Socialcommerce_Payment_Request
     */
    public function setTransaction(Socialcommerce_Payment_Transaction $transaction)
    {
        $this->_transaction = $transaction;
        return $this;
    }

    /**
     * Advanced options getter
     *
     * @return array
     */
    public function getAdvancedOptions()
    {
        return $this->_advancedOptions;
    }

    /**
     * Advanced options setter
     *
     * @param array $options
     * @return Socialcommerce_Payment_Request
     */
    public function setAdvancedOptions(array $options)
    {
        $this->_advancedOptions = $options;
        return $this;
    }
}