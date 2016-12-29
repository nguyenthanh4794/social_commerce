<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/29/2016
 * Time: 2:11 AM
 */
class Socialcommerce_OrderController extends Core_Controller_Action_Standard
{
    public function detailAction()
    {
        $order_id = $this->_getParam('order_id');
        $orderTable = Engine_Api::_()->getDbTable('orders', 'socialcommerce');
        $order = $orderTable->getByOrderId($order_id);

        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$order->isOwner($viewer)) {
            return $this -> _helper -> requireAuth() -> forward();
        }

        $order_items = $order->getItems();
        $this->view->order_id = $order_id;
        $this->view->order_items = $order_items;

        $shipping = $order->getShippingAddress();
        $this->view->aValuesShipping = $aValuesShipping = (array)json_decode($shipping->value);
        $this->view->address = $address = implode(' - ', array_values($aValuesShipping));

        list($products, $moreInfo) = $order->getProducts();

        $this->view->products = $products;
        $this->view->moreInfos = $moreInfo;

        $this->view->order = $order;
    }
}