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
}