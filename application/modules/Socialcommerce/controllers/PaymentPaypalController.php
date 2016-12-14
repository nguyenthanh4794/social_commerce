<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/8/2016
 * Time: 11:40 PM
 */
class Socialcommerce_PaymentPaypalController extends Core_Controller_Action_Standard
{
    const PAYPAL_ID = 2;
    /**
     * @return Zend_Log
     */
    public function getLog($filename = 'socialcommerce.notify.log')
    {
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/temporary/log/' . $filename);
        return new Zend_Log($writer);
    }

    public function indexAction()
    {
        $Orders = new Socialcommerce_Model_DbTable_Orders;
        $order = $Orders->fetchNew();
        $order->save();
    }

    public function gatewayAction()
    {
        $form = $this->view->form = new Socialcommerce_Form_Payment_Gateway;
    }

    protected function getBaseUrl()
    {
        $baseUrl = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.baseUrl', null);
        if (APPLICATION_ENV == 'development') {
            $request = Zend_Controller_Front::getInstance()->getRequest();
            $baseUrl = sprintf('%s://%s', $request->getScheme(), $request->getHttpHost());
            Engine_Api::_()->getApi('settings', 'core')->setSetting('socialcommerce.baseUrl', $baseUrl);
        }
        return $baseUrl;
    }

    protected function _isValidProcess()
    {
        $order_id = $this->_getParam('id');
        $order = Socialcommerce_Model_DbTable_Orders::getByOrderId($order_id);

        if (!is_object($order)) {
            $this->_forward('order-notfound');
            return false;
        }
        $gateway_id = 'paypal';

        if (!is_string($order->getPaytype())) {
            $this->_forward('paytype-notfound');
            return false;
        }

        // load paytype object.

        return true;
    }

    public function processInitAction()
    {
        if (!$this->_isValidProcess()) {
            return;
        }

        // check valid gateway has posted to.
        $order_id = $this->_getParam('id');
        $order = Socialcommerce_Model_DbTable_Orders::getByOrderId($order_id);

        $gateway = 'paypal';

        $request = new Socialcommerce_Payment_Request('init');

        $router = $this->getFrontController()->getRouter();
        $return_url = $router->assemble(array('module' => 'socialcommerce', 'controller' => 'payment-paypal', 'action' => 'review', 'id' => $order_id, 'gateway' => $gateway), 'default', true);
        $notify_url = $router->assemble(array('module' => 'socialcommerce', 'controller' => 'payment-paypal', 'action' => 'notify', 'id' => $order_id, 'gateway' => $gateway), 'default', true);
        $cancel_url = $router->assemble(array('module' => 'socialcommerce', 'controller' => 'payment-paypal', 'action' => 'cancel', 'id' => $order_id, 'gateway' => $gateway), 'default', true);
        $baseUrl = $this->getBaseUrl();
        if (!$baseUrl) $baseUrl = 'http://35.161.60.158';
        $options = array(
            'return_url' => $baseUrl . $return_url,
            'notify_url' => $baseUrl . $notify_url,
            'cancel_url' => $baseUrl . $cancel_url,
            'no_shipping' => '1',
        );

        $request->setOrder($order);
        $request->setOptions($options);
        $gatewayItem = Engine_Api::_()->getDbtable('gateways', 'payment')->find(self::PAYPAL_ID)->current();
        $config = array();
        $config['user'] = $gatewayItem->config['username'];
        $config['password'] = $gatewayItem->config['password'];
        $config['signature'] = $gatewayItem->config['signature'];
        $config['enable'] = 1;

        $payment = new Socialcommerce_Payment(array('gateway' => $gateway, 'gateway_config' => $config));
        $response = $payment->process($request);


        if ($response->isSuccess()) {
            $url = $response->getOption('redirect_url');
            if ($url) {
                return $this->_redirect($url);
            }
        }

        $this->view->response = $response;
        return $this->_forward('process-error');
    }

    public function processSaleAction()
    {
        if (!$this->_isValidProcess()) {
            return;
        }

        // check valid gateway has posted to.
        $order_id = $this->_getParam('id');
        $order = Socialcommerce_Model_DbTable_Orders::getByOrderId($order_id);

        $gateway = 'paypal';
        $payment = new Socialcommerce_Payment(array('gateway' => $gateway));
        $request = new Socialcommerce_Payment_Request('capture');


        $router = $this->getFrontController()->getRouter();
        $return_url = $router->assemble(array('module' => 'socialcommerce', 'controller' => 'payment-review', 'action' => 'review', 'id' => $order_id, 'gateway' => $gateway), 'default', true);
        $notify_url = $router->assemble(array('module' => 'socialcommerce', 'controller' => 'payment-paypal', 'action' => 'notify', 'id' => $order_id, 'gateway' => $gateway), 'default', true);
        $cancel_url = $router->assemble(array('module' => 'socialcommerce', 'controller' => 'payment-paypal', 'action' => 'cancel', 'id' => $order_id, 'gateway' => $gateway), 'default', true);

        $options = array(
            'return_url' => $this->getBaseUrl() . $return_url,
            'notify_url' => $this->getBaseUrl() . $notify_url,
            'cancel_url' => $this->getBaseUrl() . $cancel_url,
        );

        $request->setOrder($order);
        $request->setOptions($options);
        $response = $payment->process($request);
        if ($response->isSuccess()) {
            $url = $response->getOption('redirect_url');
            if ($url) {
                return $this->_redirect($url);
            }
        }
        $this->view->response = $response;

        //return $this->_forward('process-error');
    }

    public function processAction()
    {
        $this->_forward('process-init');

    }

    public function reviewAction()
    {
        if (!$this->_isValidProcess()) {
            return;
        }

        Zend_Registry::set('active_menu', 'socialcommerce_main_mycart');
        Zend_Registry::set('PAYMENTMENU_ACTIVE', 'payment-confirm');
        $form = $this->view->form = new Socialcommerce_Form_Payment_Review;
        // get result from review action
        $token = $this->_getParam('token');
        $payer_id = $this->_getParam('PayerID');
        $order_id = $this->_getParam('id');
        $gateway = 'paypal';
        $this->view->id = $order_id;
        $order = Socialcommerce_Model_DbTable_Orders::getByOrderId($order_id);
        $this->view->order = $order;

        $shipping = $order->getShippingAddress();
        $this->view->aValuesShipping = $aValuesShipping = (array)json_decode($shipping->value);
        $this->view->address = $address = implode(' - ', array_values($aValuesShipping));
        list($products, $moreInfo) = $order->getProducts();

        $this->view->products = $products;
        $this->view->moreInfos = $moreInfo;

        $baseUrl = $this->getBaseUrl();
        if (!$baseUrl) $baseUrl = 'http://35.161.60.158';
        $allParams = $this->_getAllParams();

        if ($this->_request->isGet()) {
            return;
        }

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            // get the payment
            $payment = new Socialcommerce_Payment(array('gateway' => $gateway));

            // set request order
            $request = new Socialcommerce_Payment_Request('sale');
            $order = Socialcommerce_Model_DbTable_Orders::getByOrderId($order_id);
            $request->setOrder($order);

            // get notify url
            $router = $this->getFrontController()->getRouter();
            $notify_url = $router->assemble(array('module' => 'socialcommerce', 'controller' => 'payment-paypal', 'action' => 'notify', 'id' => $order_id), 'default', true);

            // check request option
            $options = array(
                'token' => $token,
                'payer_id' => $payer_id,
                'notify_url' => $baseUrl . $notify_url,
            );
            $request->setOptions($options);

            // process plugin
            $plugin = $order->getPlugin();
            try {
                // process request.
                $response = $payment->process($request);
                // log response result.
                $response_options = $response->getOptions();
                $this->getLog('socialcommerce.response.log')->log(var_export($response_options,true), Zend_Log::DEBUG);

                /**
                 * add transaction
                 */
                Socialcommerce_Api_Transaction::getInstance()->addTransaction($allParams, $order->toArray(), $response->getOptions()->toArray());
                // get payment status
                $status = $response->getOption('payment_status');
                $status = strtolower($status);
                $this->getLog('socialcommerce.status.log')->log(var_export($status,true), Zend_Log::DEBUG);
                // cucess result
                if ($response->isSuccess()) {
                    // process plugin.
                    if ($status == 'pending') {
                        $plugin->onPending();
                        return $this->_forward('process-pending');
                    } else if ($status == 'completed') {
                        $plugin->onSuccess();
                    } else if ($status == 'cancel') {
                        $plugin->onCancel();
                    } else {
                        $plugin->onFailure();
                    }
                    return $this->_forward('process-success');
                } else {
                    // failture procss
                    $plugin->onFailure();

                    // foward to process error.
                    $this->view->response = $response;
                    $this->_forward('process-error');
                }

                /**
                 * clean current session
                 */
                $cart = Socialcommerce_Api_Cart::getInstance()->flushCurrentOrder();

            } catch (Exception $e) {
                $this->getLog('store.error.log')->log($e->getMessage(), Zend_Log::ERR);
                // foward to process error.
                $this->view->response = $response;
                $this->_forward('process-error');
            }
        }
    }

    public function acceptAction()
    {
        $params = $this->getRequest()->getParams();
        $log_message = var_export($params, true);
        $this->getLog()->log($log_message, Zend_Log::DEBUG);
    }

    public function cancelAction()
    {
        $params = $this->getRequest()->getParams();
        $log_message = var_export($params, true);

        $this->getLog()->log($log_message, Zend_Log::DEBUG);
    }

    public function notifyAction()
    {
        $params = $this->getRequest()->getParams();
        $log_message = var_export($params, true);

        $this->getLog()->log($log_message, Zend_Log::DEBUG);
    }

    protected function _getCheckoutDetails()
    {
        $gateway = 'paypal';

        $payment = Socialcommerce_Payment::factory(array('gateway' => $gateway));

        $request = new Socialcommerce_Payment_Request('CheckoutDetails');

        // set request token
        $request->setOptions(array('token' => $this->_getParam('token')));

        $response = $payment->process($token);

        //'amount','currency',''

    }


    public function orderNotfoundAction()
    {
        #invalid order id.
    }

    public function paytypeNotfoundAction()
    {
        #invalid order id.
    }

    public function processErrorAction()
    {
        $order_id = $this->_getParam('id');
        $this->view->order = $order = Socialcommerce_Model_DbTable_Orders::getByOrderId($order_id);

    }

    public function processSuccessAction()
    {
        $order_id = $this->_getParam('id');
        $this->view->order = $order = Socialcommerce_Model_DbTable_Orders::getByOrderId($order_id);

    }

    public function processPendingAction()
    {
        $order_id = $this->_getParam('id');
        $this->view->order = $order = Socialcommerce_Model_DbTable_Orders::getByOrderId($order_id);

    }
}