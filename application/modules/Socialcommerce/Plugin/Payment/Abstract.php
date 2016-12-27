<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 6:26 PM
 */
abstract class Socialcommerce_Plugin_Payment_Abstract
{
    /**
     * @see  schema.engine4_commerce_paytypes
     * @var  string
     */
    protected $_payType;

    /**
     * @see  schema.engine4_commerce_orders
     * @var  string
     */
    protected $_orderId;

    /**
     * @var Socialcommerce_Model_Order
     */
    protected $_order;

    /**
     * @param   string $order_id
     * @return Socialcommerce_Plugin_Payment_Abstract
     */
    public function setOrderId($order_id)
    {
        $this->_orderId = $order_id;
        return $this;
    }

    /**
     * @return  string
     */
    public function getOrderId()
    {
        if ($this->_orderId == null) {
            throw new Exception("order id has not set.");
        }
        return $this->_orderId;
    }

    /**
     * @param  string $pay_type
     * @return Socialcommerce_Plugin_Payment_Abstract
     */
    public function setPaytype($pay_type)
    {
        $this->_payType = $pay_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaytype()
    {
        if ($this->_payType == null) {
            throw new Exception("Paytype has not set");
        }
        return $this->_payType;
    }

    /**
     * @return Socialcommerce_Model_Order
     */
    public function getOrder()
    {
        if ($this->_order == null) {
            throw new Exception("Order has not set");
        }
        return $this->_order;
    }

    /**
     * @param Socialcommerce_Model_Order $order
     * @return Socialcommerce_Plugin_Payment_Abstract
     */
    public function setOrder(Socialcommerce_Model_Order $order)
    {
        $this->_order = $order;
        $this->_payType = $order->getPaytype();
        $this->_orderId = $order->getId();
        return $this;
    }

    /**
     * @return Socialcommerce_Model_DbTable_OrderItems
     */
    public function getModelOrderItems()
    {
        return new Socialcommerce_Model_DbTable_OrderItems;
    }

    public function getByObjectId($object_id)
    {
        $order_id = $this->getOrder()->getId();
        $Items = $this->getModelOrderItems();
        $select = $Items->select()->where('order_id=?', $order_id)->where('object_id=?', (string)$object_id);
        return $Items->fetchRow($select);
    }

    abstract function onSuccess();

    abstract function onPending();

    abstract function onFailure();

    abstract function onCancel();

    public function noBilling()
    {
        return true;
    }

    abstract function addItem($item, $qty = 1, $params);

    public function noShipping()
    {
        return true;
    }
}