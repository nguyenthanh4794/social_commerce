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
        $gateway = $this->_getParam('gateway', '');

        $form = $this->view->form = new Socialcommerce_Form_Payment_Shipping();

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        // Process
        $table = Engine_Api::_()->getDbTable('shippingaddresses', 'socialcommerce');
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
            // Create blog
            $address = $form->saveValues($order_id);
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

        if ($order->guest_id == 0 && $order->owner_id != $viewer->getIdentity()) {
            $this->_forward('order-notfound');
            return;
        }

        $this->view->order = $order;

        if (Zend_Registry::isRegistered('review-done')) {
            Zend_Registry::set('review-done', 0);
        }

        $shipping = $order->getShippingAddress();
        $aValuesShipping = (array)json_decode($shipping->value);
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

    public function gatewayAction() {
        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->_helper->requireSubject()->forward();
        }

        $order = Engine_Api::_()->core()->getSubject();

        if (!$order) {
            return $this->_helper->requireSubject()->forward();
        }

        $order_id = $order->order_id;

        $this->view->form = $form = new Socialcommerce_Form_Payment_Gateway;
    }
}