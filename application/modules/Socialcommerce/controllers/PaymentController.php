<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 8:21 PM
 */
class Socialcommerce_PaymentController extends Core_Controller_Action_Standard
{
    public function init()
    {
        $order_id = $this->_getParam('id');

        if ($order_id) {
            $order = Socialcommerce_Model_DbTable_Orders::getByOrderId($order_id);

            if ($order && !Engine_Api::_()->core()->hasSubject('socialcommerce_order')) {
                Engine_Api::_()->core()->setSubject($order);
            }
        }
    }

    public function processAction()
    {
        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->_helper->requireSubject()->forward();
        }

        $order = Engine_Api::_()->core()->getSubject();
        if (!$order) {
            return $this->_helper->requireSubject()->forward();
        }

        $viewer = Engine_Api::_()->user()->getViewer();
        $order_id = $order->order_id;

        if ($order->guest_id == 0 && $order->owner_id != $viewer->getIdentity()) {
            $this->_forward('order-notfound');
            return;
        }
        if (!is_object($order)) {
            $this->_forward('order-notfound');
            return;
        }

        if (!is_string($order->getPaytype())) {
            $this->_forward('paytype-notfound');
            return;
        }

        if (!$order->noShipping() && !is_object($order->getShippingAddress())) {
            $this->_helper->redirector->gotoRoute(array('controller' => 'payment', 'action' => 'shipping-address', 'id' => $order_id), 'socialcommerce_extended');
            return;
        }

        if ($order->paytype_id == 'shopping-cart' && (!($this->_getParam('review') || $this->_getParam('review') != 'done'))) {
            $this->_helper->redirector->gotoRoute(array('controller' => 'payment', 'action' => 'review-order', 'id' => $order_id), 'socialcommerce_extended');
        }

        $gateway = $_POST['gateway'];

        if (!$gateway) {
            $this->_forward('gateway');
            return;
        }

        // load paytype object.
        if ($gateway != "google") {
            if ($gateway == 'paypaladaptive') {
                $gateway = 'paypal-adaptive';
            }
            $this->_helper->redirector->gotoSimple('process', 'payment-' . $gateway, 'socialcommerce', array('id' => $order_id));
        }
    }

    public function shippingAddressAction()
    {
        Zend_Registry::set('PAYMENTMENU_ACTIVE', 'shipping-address');

        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->_helper->requireSubject()->forward();
        }
        $order = Engine_Api::_()->core()->getSubject();

        if (!$order) {
            return $this->_helper->requireSubject()->forward();
        }

        $viewer = Engine_Api::_()->user()->getViewer();

        if ($order->guest_id == 0 && $order->owner_id != $viewer->getIdentity()) {
            $this->_forward('order-notfound');
            return;
        }
        $order_id = $order->order_id;

        $this->view->order_id = $order_id;

        Zend_Registry::set('order_id', $order_id);

        // GET SHIPPING INFORMATION OF CURRENT USER
        $shippingInfoTable = Engine_Api::_()->getDbTable('shippingAddresses', 'socialcommerce');
        $this->view->shipping_infos = $shipping_infos = $shippingInfoTable->getShippingInfosByUserId($viewer->getIdentity());

        // FORM TO SUBMIT
        $this->view->form = $form = new Socialcommerce_Form_Payment_ShippingInformation();

        // ADD OPTION TO INFO ARRAY TO VALIDATE FORM
        foreach ($shipping_infos as $shipping_info) {
            $form->shippinginformation_id->addMultiOption($shipping_info->getIdentity(), $shipping_info->getIdentity());
        }

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {
            // Create blog
            $order->shipping_id = $this->_getParam('shippinginformation_id');
            $order->save();

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }

        return $this->_helper->redirector->gotoRoute(array('action' => 'review-order', 'id' => $order_id));
    }

    /**
     *
     * Order Review
     */

    public function reviewOrderAction()
    {
        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->_helper->requireSubject()->forward();
        }

        $order = Engine_Api::_()->core()->getSubject();

        if (!$order) {
            return $this->_helper->requireSubject()->forward();
        }

        $order_id = $order->order_id;
        $this->view->id = $order_id;
        Zend_Registry::set('order_id', $order_id);
        $viewer = Engine_Api::_()->user()->getViewer();

        if ($order->owner_id != $viewer->getIdentity()) {
            $this->_forward('order-notfound');
            return;
        }

        $this->view->order = $order;

        if (Zend_Registry::isRegistered('review-done')) {
            Zend_Registry::set('review-done', 0);
        }

        $shipping = $order->getShippingAddress();
        $this->view->aValuesShipping = $aValuesShipping = (array)json_decode($shipping->value);
        $this->view->address = $address = implode(' - ', array_values($aValuesShipping));
        list($products, $moreInfo) = $order->getProducts();

        $this->view->products = $products;
        $this->view->moreInfos = $moreInfo;

        $this->view->form = $form = new Socialcommerce_Form_Payment_OrderReview();

        if (!$this->_request->isPost()) {
            return;
        }
        if ($form->isValid($this->_request->getPost())) {
            $this->_helper->redirector->gotoRoute(array('controller' => 'payment', 'action' => 'process', 'id' => $order_id, 'review' => 'done'), 'socialcommerce_extended');
        }
    }

    public function gatewayAction()
    {
        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->_helper->requireSubject()->forward();
        }

        $order = Engine_Api::_()->core()->getSubject();

        if (!$order) {
            return $this->_helper->requireSubject()->forward();
        }
    }
}