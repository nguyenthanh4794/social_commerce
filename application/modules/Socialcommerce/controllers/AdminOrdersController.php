<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 3:11 PM
 */
class Socialcommerce_AdminOrdersController extends Core_Controller_Action_Admin
{
    public function init()
    {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_main_orders');
    }

    public function getDbTable()
    {
        return Engine_Api::_()->getDbTable('orders', 'socialcommerce');
    }

    public function indexAction()
    {
        $page = $this->_getParam('page', 1);
        $this->view->form = $form = new Socialcommerce_Form_Admin_Orders_Search();
        $values = array();
        if ($form->isValid($this->_getAllParams())) {
            $values = $form->getValues();

        }
        $limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.page', 10);
        $values['limit'] = $limit;
        $values['paytype_id'] = 'shopping-cart';
        $this->view->paginator = Engine_Api::_()->getDbTable('orders', 'socialcommerce')->getOrdersPaginator($values);
        $this->view->paginator->setItemCountPerPage($limit);
        $this->view->paginator->setCurrentPageNumber($page);
        $this->view->formValues = $values;
    }

    public function orderDetailAction()
    {
        $order_id = $this->_getParam('order_id');
        $orderTable = $this->getDbTable();
        $order = $orderTable->getByOrderId($order_id);
        $order_items = $order->getItems();
        $this->view->order_id = $order_id;
        $this->view->order_items = $order_items;

        $shipping = $order->getShippingAddress();
        $aValuesShipping = (array)json_decode($shipping->value);
        $this->view->address = $address = implode(' - ', array_values($aValuesShipping));

        list($products, $moreInfo) = $order->getProducts();

        $this->view->products = $products;
        $this->view->moreInfos = $moreInfo;

        $this->view->order = $order;
    }
}