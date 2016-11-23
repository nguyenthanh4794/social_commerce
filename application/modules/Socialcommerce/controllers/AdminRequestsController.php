<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 12:35 PM
 */
class Socialcommerce_AdminRequestsController extends Core_Controller_Action_Admin
{
    public function init()
    {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_main_requests');
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

    public function getDbTable()
    {
        return Engine_Api::_()->getDbTable('requests', 'socialcommerce');
    }

    public function indexAction()
    {
        $table = $this->getDbTable();
        $select = $table->select()->setIntegrityCheck(false)->from(array('req' => 'engine4_socialcommerce_requests'))->join(array('u' => 'engine4_users'), 'u.user_id=req.owner_id')->order('req.request_date desc');

        $paginator = $this->view->paginator = Zend_Paginator::factory($select);
        $page = $this->_getParam('page', 1);

        $paginator->setCurrentPageNumber($page);
    }

    public function acceptAction()
    {
        $this->view->form = $form = new Socialcommerce_Form_Payment_Admin_Request_Accept;
        $table = $this->getDbTable();
        $id = $this->_getParam('id', 0);
        $this->view->request = $item = $table->find($id)->current();
        $gateway = 'paypal';
        $this->view->responseMessage = $item->response_message;
        $this->view->account = $account = $item->getAccount();

        $this->view->currency = $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
        $amount = $item->request_amount;
        $baseUrl = $this->getBaseUrl();
        $router = $this->getFrontController()->getRouter();
        $returnUrl = $this->view->returnUrl = $baseUrl . $router->assemble(array(
                'module' => 'socialcommerce',
                'controller' => 'request',
                'action' => 'index',
                'id' => $item->getIdentity(),
                'owner-id' => $item->owner_id,
                'stall-id' => $item->stall_id,
            ), 'admin_default', true);

        $cancelUrl = $this->view->cancelUrl = $baseUrl . $router->assemble(array(
                'module' => 'socialcommerce',
                'controller' => 'request',
                'action' => 'index',
                'id' => $item->getIdentity(),
                'owner-id' => $item->owner_id,
                'stall-id' => $item->stall_id,
            ), 'admin_default', true);

        $notifyUrl = $this->view->notifyUrl = $baseUrl . $router->assemble(array(
                'module' => 'socialcommerce',
                'controller' => 'request-callback',
                'action' => 'notify',
                'id' => $item->getIdentity(),
                'owner-id' => $item->owner_id,
                'stall-id' => $item->stall_id
            ), 'default', true);

        $this->view->sandboxMode = $sandboxMode = Socialcommerce_Api_Core::isSandboxMode();

        if ($sandboxMode) {
            $this->view->formUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $this->view->formUrl = 'https://www.paypal.com/cgi-bin/webscr';
        }
    }

    public function acceptMasspayAction()
    {
        $gateway = 'paypal';
        $payment = Socialcommerce_Payment::factory($gateway);
        $request = new Socialcommerce_Payment_Request('MassPay');
        $this->view->form = $form = new Socialcommerce_Form_Payment_Admin_Request_Accept;

        $table = new Socialcommerce_Model_DbTable_Requests;
        $id = $this->_getParam('id', 0);
        $item = $table->find($id)->current();

        if (!is_object($item)) {
            // item not found
        }

        if (!$item->isWaitingToProcess()) {
            // this item is processed before
        }

        $account = $item->getAccount();

        $options = array(
            'currency' => Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD'),
            'pay_items' => array(
                array('email' => $account->account_username, 'amount' => $item->request_amount),
            ),
        );


        $request->setOptions($options);
        $payment->process($request);
    }

    public function denyAction()
    {
        $this->_helper->layout->setLayout('admin-simple');
        $this->view->form = $form = new Socialcommerce_Form_Admin_Payment_Request_Deny;

        $req = $this->getRequest();

        $table = new Socialcommerce_Model_DbTable_Requests;
        $id = $this->_getParam('id', 0);
        $item = $table->find($id)->current();

        if (!is_object($item)) {

        }

        if ($req->isGet()) {
            return;
        }

        if ($req->isPost() && $form->isValid($req->getPost())) {
            $data = $form->getValues();

            $errors = false;

            if ($errors) {
                $form->markAsError();
                return;
            }
            // process request.
            $item->request_status = 'denied';
            $item->setFromArray($data);
            $item->response_date = date('Y-m-d H:i:s');
            $item->save();

            $sendTo = Engine_Api::_()->getItem('user', $item->owner_id);
            $params = $item->toArray();
            Engine_Api::_()->getApi('mail', 'Socialcommerce')->send($sendTo, 'stall_requestdeny', $params);

            // Send Email Deny to Request
        }

        $this->_forward('success', 'utility', 'core', array('smoothboxClose' => true, 'parentRefresh' => true, 'format' => 'smoothbox', 'messages' => array('Denied Successfully.')));
    }

    public function requestPaymentAction()
    {
        if (!$this->_helper->requireUser()->isValid()) {
            return;
        }
        //tat di layout
        $id = $this->getRequest()->getParam('id');
        $status = $this->getRequest()->getParam('status');
        $this->view->is_adaptive_payment = 0;//$is_adaptive_payment;
        if ($status == 0) {
            $_SESSION['payment_sercurity_adminpayout'] = Socialcommerce_Api_Account::getSecurityCode($id);
            $account = Socialcommerce_Api_Account::getFinanceAccountRequestsByRequestId();
            $paymentForm = Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'socialcommerce_admin_main_requests', true);

            if ($account['request_amount '] > $account['total_amount']) {

                echo $this->view->translate('Invalid request.Total amount request is larger than total user amount');
                return false;
            } else {
                $_SESSION['total_amount'] = $account['total_amount'];
            }
            $this->view->account = $account;
            $this->view->paymentForm = $paymentForm;
            $this->view->sercurity = $_SESSION['payment_sercurity_adminpayout'];
            $this->view->core_path = $this->selfURL();
            $this->view->status = $status;
        } else {
            $_SESSION['payment_sercurity_adminpayout'] = Socialcommerce_Api_Cart::getSecurityCode();
            $account = Socialcommerce_Api_Account::getFinanceAccountRequestsByRequestId($id);

            $request_id = $this->_getParam('id');
            if ($account['request_amount'] > $account['total_amount']) {
                echo $this->view->translate('Invalid request.Total amount request is larger than total user amount');
                return false;
            } else {
                $_SESSION['total_amount'] = $account['total_amount'];
            }

            $method_payment = 'directly';
            $gateway_name = "paypal";
            $gateway = Socialcommerce_Api_Cart::loadGateWay($gateway_name);
            $paypal = Engine_Api::_()->getItem('payment_gateway', 2);
            $config = (array)$paypal->config;
            $settings = array(
                'env' => ($paypal->test_mode) ? 'sandbox' : 'real',
                'api_username' => $config['username'],
                'api_password' => $config['password'],
                'api_signature' => $config['signature'],
                'use_proxy' => false,
            );
            $gateway->set($settings);
            $receiver = Engine_Api::_()->user()->getViewer();
            $query_string = http_build_query(array(
                'req4' => $_SESSION['payment_sercurity_adminpayout'],
                'qtotal_amount' => $_SESSION['total_amount'],
                'qrequest_id' => $request_id,
                'qreceiver' => $receiver,
                'message' => $_SESSION['message'],
            ));

            $returnUrl = $this->selfURL() . 'application/modules/Socialcommerce/externals/scripts/redirectRequest.php?pstatus=success&' . $query_string . '&req5=';
            $cancelUrl = $this->selfURL() . 'application/modules/Socialcommerce/externals/scripts/callback.php?action=callbackRequest&pstatus=cancel&' . $query_string . '&req5=';
            $notifyUrl = $this->selfURL() . 'application/modules/Socialcommerce/externals/scripts/callback.php?action=callbackRequest&' . $query_string . '&req5=';

            list($receiver, $paramsPay) = Socialcommerce_Api_Cart::getParamsPay($gateway_name, $returnUrl, $cancelUrl, $method_payment, $notifyUrl);
            $_SESSION['receiver'] = $receiver;

            if ($settings['env'] == 'sandbox') {
                $paymentForm = "https://www.sandbox.paypal.com/cgi-bin/webscr";
            } else {
                $paymentForm = "https://www.paypal.com/cgi-bin/webscr";
            }
            $request_info = Socialcommerce_Api_Cart::getPaymentRequest($account['paymentrequest_id']);
            $_SESSION['request_info_user_id'] = $request_info['request_user_id'];
            $security_code = Socialcommerce_Api_Cart::getSecurityCode();
            if ($request_info != null) {
                $paramsPay['receivers'] = array(
                    array('email' => $request_info['account_username'], 'amount' => $request_info['send_amount'], 'invoice' => $security_code),
                );
            }

            $this->view->paymentForm = $paymentForm;
            $this->view->sercurity = $_SESSION['payment_sercurity_adminpayout'];
            $this->view->core_path = $this->selfURL();
            $this->view->account = $account;
            $this->view->status = $status;
            $this->view->receiver = $paramsPay['receivers'][0];
            $this->view->currency = Socialcommerce_Api_Core::getDefaultCurrency();
            $this->view->paramPay = $paramsPay;
        }

    }
}