<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 11:28 PM
 */
class Socialcommerce_AccountController extends Core_Controller_Action_Standard
{
    public function init()
    {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('socialcommerce_main', array(), 'socialcommerce_main_account');
        $this->_paginate_params['page'] = $this->getRequest()->getParam('page', 1);
        $this->_paginate_params['limit'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.page', 10);
    }

    public function createAction()
    {
        // only members can create account
        if (!$this->_helper->requireUser()->isValid()) return;
        $viewer = Engine_Api::_()->user()->getViewer();
        $this->view->form = $form = new Socialcommerce_Form_CreateAccount();
        if (!empty($viewer->email)) {
            $form->populate(array('account_username' => $viewer->email));
        }
        $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $info = Socialcommerce_Api_Account::getCurrentInfo($user_id);

        if ($info['currency'])
            $form->removeElement('currency');
        $is_account = Socialcommerce_Api_Account::getCurrentInfo($viewer->getIdentity());
        if ($is_account['account_username'] != null)
            $result = 1;
        if ($this->getRequest()->isPost() && $this->view->form->isValid($this->getRequest()->getPost())) {
            $db = Engine_Api::_()->getDbTable('paymentAccounts', 'socialcommerce')->getAdapter();
            $db->beginTransaction();
            try {
                $result = $this->view->form->saveValues();
                $this->view->result = $result;
                $db->commit();
                if ($result)
                    return $this->_redirect('social-commerce/account');
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        }
    }

    public function indexAction()
    {
        if (!$this->_helper->requireUser()->isValid()) return;

        $this->_helper->content
            ->setEnabled();

        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
        $user_id = $viewer->getIdentity();
        $values = $this->getRequest()->getParams();
        $this->view->filter_form = $filter_form = new Socialcommerce_Form_Account_SearchRequest();
        $params = $this->_getAllParams();
        $filter_form->populate($params);
        // Query requests
        $rqTable = Engine_Api::_()->getDbtable('requests', 'socialcommerce');
        $select = $rqTable->select()->where("owner_id = ?", $user_id);
        $post = $this->getRequest()->getPost();
        if (!$filter_form->isValid($post))
            return;
        if (isset($params['request_from']) && $params['request_from']) {
            $select->where("request_date >= ?", date('Y-m-d', strtotime($params['request_from'])));
        }

        if (isset($params['request_to']) && $params['request_to']) {
            $select->where("request_date <= ?", date('Y-m-d', strtotime($params['request_to'])));
        }

        if (isset($params['response_from']) && $params['response_from']) {
            $select->where("response_date >= ?", date('Y-m-d', strtotime($params['response_from'])));
        }

        if (isset($params['response_to']) && $params['response_to']) {
            $select->where("response_date <= ?", date('Y-m-d', strtotime($params['response_to'])));
        }

        if (isset($params['status']) && $params['status'] != "") {
            $select->where("request_status = ?", $params['status']);
        }

        $items_per_page = Engine_Api::_()->getApi('settings', 'core')->socialcommerce_page;
        // echo $select->__toString();
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($items_per_page);
        if (isset($values['page']))
            $this->view->paginator = $paginator->setCurrentPageNumber($values['page']);


        $info_user = Socialcommerce_Api_Account::getCurrentInfo($user_id);

        if (empty($info_user['account_id']))
            return $this->_forward('info', 'seller', 'socialcommerce');

        $orderItemTable = Engine_Api::_()->getDbtable('orderItems', 'socialcommerce');
        $ttTableName = $orderItemTable->info('name');
        $select = $orderItemTable->select();

        $select->from($ttTableName, array("$ttTableName.*"))
            ->setIntegrityCheck(false)
            ->where("$ttTableName.owner_id = ?", $user_id);

        $orderItems = $orderItemTable->fetchAll($select);

        $info_account = Socialcommerce_Api_Account::getCurrentAccount($user_id);

        $total_sold = Socialcommerce_Api_Account::getTotalSold($orderItems);
        $total_commission = Socialcommerce_Api_Account::getTotalCommission($orderItems);
        $waiting_amount = Socialcommerce_Api_Account::getWaitingAmount($user_id);
        $received_amount = Socialcommerce_Api_Account::getReceivedAmount($user_id);

        if (strlen($info_user['status']) >= 41)
            $info_user['status'] = substr($info_user['status'], 0, 41) . "...";
        $amount_seller = Socialcommerce_Api_Account::getAmountSeller($orderItems);

        $min_payout = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.minWithdrawSeller', 5.00);
        $max_payout = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.maxWithdrawSeller', 100.00);

        $allow_request = 0;
        $requested_amount = Socialcommerce_Api_Account::getTotalRequest($user_id, 'payment');
        $refund_amount = Socialcommerce_Api_Account::getTotalRequest($user_id, 'refund');

        $rest = $amount_seller - $requested_amount;
        if ($rest >= $min_payout) {
            $allow_request = 1;
        }
        $this->view->info_user = $info_user;
        $this->view->info_account = $info_account;
        $this->view->min_payout = $min_payout;
        $this->view->max_payout = $max_payout;
        $this->view->allow_request = $allow_request;
        $this->view->requested_amount = round($requested_amount + $refund_amount, 2);
        $this->view->current_amount = round($rest, 2);
        $this->view->total_sold = $total_sold;
        $this->view->total_commission = $total_commission;
        $this->view->waiting_amount = $waiting_amount;
        $this->view->received_amount = $received_amount;

        Zend_Registry::set('SELLERMENU_ACTIVE', 'account');
    }

    public function editAction()
    {
        if (!$this->_helper->requireUser()->isValid()) return;
        $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $info = Socialcommerce_Api_Account::getCurrentInfo($user_id);
        $info['full_name'] = $info['displayname'];
        $this->view->info = $info;
        $this->view->form = $form = new Socialcommerce_Form_Account_Edit();
        if ($info['currency'])
            $form->removeElement('currency');
        $form->populate($info);
        $post = $this->getRequest()->getPost();
        if (!isset($post['full_name']))
            return;
        if ($post['full_name'] == "")
            return;
        if (!$form->isValid($post))
            return;
        $email = $form->getValue('account_username');
        if (trim($email) == "") {
            $form->getElement('account_username')->addError('Please enter valid email!');
            return;
        } else if (trim($email) != "") {
            $regexp = "/^[A-z0-9_]+([.-][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/";
            if (!preg_match($regexp, $email)) {
                $form->getElement('account_username')->addError('Please enter valid email!');
                return;
            }
        }
        $aVals = $form->getValues();
        $aVals['displayname'] = $aVals['full_name'];
        $result = Socialcommerce_Api_Account::updateinfo($aVals);
        $paymentaccount = Socialcommerce_Api_Cart::getFinanceAccount($user_id, 2);
        Socialcommerce_Api_Account::updateusername_account($paymentaccount['paymentaccount_id'], $aVals['account_username']);
        if (isset($aVals['currency'])) {
            Socialcommerce_Api_Account::updatecurrency_account($paymentaccount['paymentaccount_id'], $aVals['currency']);
            $form->removeElement('currency');
        }
        $info_account = Socialcommerce_Api_Account::getCurrentAccount($user_id);
        if ($info_account != null) {
            if ($info_account['payment_type'] == 1) {
                $params['admin_account'] = $aVals['account_username'];
                $params['is_from_finance'] = 1;
                Socialcommerce_Api_Gateway::saveSettingGateway('paypal', $params);
            }
            $info = Socialcommerce_Api_Account::getCurrentInfo($user_id);
            $info['full_name'] = $info['displayname'];
            $form->addNotice('Your changes have been saved.');
        }
        $this->view->info = $info;


    }

    public function selfURL()
    {
        $server_array = explode("/", $_SERVER['PHP_SELF']);
        $server_array_mod = array_pop($server_array);
        if ($server_array[count($server_array) - 1] == "admin") {
            $server_array_mod = array_pop($server_array);
        }
        $server_info = implode("/", $server_array);
        $http = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
        return $http . $_SERVER['HTTP_HOST'] . $server_info . "/";
    }

    public function thresholdAction()
    {
        if (!$this->_helper->requireUser()->isValid()) {
            return;
        }
        $this->_helper->layout->setLayout('admin-simple');
        $this->view->form = $form = new Socialcommerce_Form_Account_Request();
        $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();

            $current_money = $values['txtrequest_money'];

            $viewer = Engine_Api::_()->user()->getViewer();
            $commission = Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('socialcommerce_product', $viewer, 'commission', 5);
            if (empty($commission)) {
                $commission = 5;
            }

            if (!is_numeric($current_money)) {
                $current_money = -10;
            }

            $info_account = Socialcommerce_Api_Account::getCurrentAccount(Engine_Api::_()->user()->getViewer()->getIdentity());
            $TotalRequest = Socialcommerce_Api_Account::getTotalRequest(Engine_Api::_()->user()->getViewer()->getIdentity());

            $this->view->min_payout = $min_payout = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.minWithdrawSeller', 5.00);
            $this->view->max_payout = $max_payout = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.maxWithdrawSeller', 100.00);
            $allow_request = 0;

            if (round(($info_account['total_amount'] - $TotalRequest), 2) >= round($current_money, 2)) {
                if ($current_money != -10 && $current_money > 0) {
                    if ($max_payout == -1 || $max_payout >= $current_money) {
                        $allow_request = 1;
                    }
                }
            }

            if ($allow_request == 1) {
                $vals = array();
                $vals['owner_id'] = Engine_Api::_()->user()->getViewer()->getIdentity();
                $vals['request_amount'] = round($current_money, 2);
                $vals['request_date'] = date('Y-m-d H:i:s');
                $vals['request_message'] = strip_tags($values['textarea_request']);
                $vals['request_status'] = 'waiting';
                $vals['commission'] = $commission;

                $vals['tc_account_id'] = $info_account['account_id'];
                $request_id = Socialcommerce_Api_Account::insertRequest($vals);
                Socialcommerce_Api_Account::getCurrentAccount(Engine_Api::_()->user()->getViewer()->getIdentity());
            }
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => !empty($request_id) ? array('Your request has successfully sent.') : array(''),
            ));
        }

        $this->view->currency = $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
        $this->view->min_payout = $min_payout = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.minWithdrawSeller', 5.00);
        $this->view->max_payout = $max_payout = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.maxWithdrawSeller', 100.00);
        $this->_helper->layout->setLayout('default-simple');
        // Output
        $this->renderScript('account/form.tpl');
    }

    public function requestmoneyAction()
    {
        if (!$this->_helper->requireUser()->isValid()) {
            return;
        }
        $current_money = $this->getRequest()->getParam('currentmoney');
        $currency = Engine_Api::_()->socialcommerce()->getDefaultCurrency();
        $viewer = Engine_Api::_()->user()->getViewer();
        $commission = Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('socialcommerce_product', $viewer, 'commission', 5);
        if ($commission == "") {
            $mtable = Engine_Api::_()->getDbtable('permissions', 'authorization');
            $maselect = $mtable->select()
                ->where("type = 'socialcommerce_deal'")
                ->where("level_id = ?", $viewer->level_id)
                ->where("name = 'commission'");
            $mallow_a = $mtable->fetchRow($maselect);
            if (!empty($mallow_a))
                $commission = $mallow_a['value'];
            else
                $commission = 0;
        }
        if (!is_numeric($current_money))
            $current_money = -10;

        if (round($current_money, 2) - $current_money != 0) {
            $html = '<h2>Invalid request number .</h2>';
            echo '{"html":"' . $html . '"}';
            return false;
        }
        $info_account = Socialcommerce_Api_Account::getCurrentAccount(Engine_Api::_()->user()->getViewer()->getIdentity());
        $TotalRequest = Socialcommerce_Api_Account::getTotalRequest(Engine_Api::_()->user()->getViewer()->getIdentity());

        $min_payout = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.minWithdrawSeller', 5.00);
        $max_payout = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.maxWithdrawSeller', 100.00);
        $allow_request = 0;
        $warning = 0;
        $current_money_money = 0;
        $current_request_money = 0;
        if (round(($info_account['total_amount'] - $TotalRequest - $min_payout), 2) >= round($current_money, 2)) {
            if ($current_money != -10 && $current_money > 0) {
                if ($max_payout == -1 || $max_payout >= $current_money) {
                    $allow_request = 1;
                }
            }
        } else if ($current_money <= $max_payout) {
            $warning = 1;
            $minhrequest = round($info_account['total_amount'] - $TotalRequest - $min_payout, 2);
            if ($minhrequest < 0)
                $minhrequest = 0;
            $html = "You have requested " . round($TotalRequest, 2) . "  before, so you only can request maximum is " . $minhrequest . " USD.";
        } else {
            $warning = 1;
            $html = "You only can request maximum is " . $max_payout . "  for each time";
        }
        if ($allow_request == 1) {
            $vals = array();
            $vals['request_user_id'] = Engine_Api::_()->user()->getViewer()->getIdentity();
            $vals['request_amount'] = round($current_money, 2);
            $vals['request_date'] = date('Y-m-d H:i:s');
            $vals['request_message'] = strip_tags($this->getRequest()->getParam('reason'));
            $vals['request_status'] = 0;

            $vals['request_type'] = 1;
            $vals['dealbuy_id'] = 0;
            $vals['request_payment_acount_id'] = $info_account['paymentaccount_id'];
            $request_id = Socialcommerce_Api_Account::insertRequest($vals);
            $info_account = Socialcommerce_Api_Account::getCurrentAccount(Engine_Api::_()->user()->getViewer()->getIdentity());

            $html = "<h2>Request successfully!<h2><p class='description'><p>";
            $current_request_money = round($TotalRequest + $current_money, 2);
            $current_money_money = round($info_account['total_amount'] - $TotalRequest - $current_money, 2);
        } else if ($warning != 1) {
            $html = "<h2>Request false!</h2><p class='description'><p>";
        }
        return $this->_helper->redirector->gotoRoute(array('action' => 'index'), 'socialcommerce_account', true);
        //echo '{"html":"'.$html.'","current_request_money":"'.$current_request_money.'","current_money_money":"'.$current_money_money.'"}';
    }

    public function loadMessageAction()
    {
        $type = $this->_getParam('type', 'request');
        $request_id = $this->_getParam('request_id', 0);
        $request = Engine_Api::_()->getItem('socialcommerce_request', $request_id);
        if ($request == null)
            return $this->_helper->requireSubject()->forward();
        if ($type == 'request') {
            $this->view->title = "Request message";
            $this->view->message = $request->request_message;
        } else {
            $this->view->title = "Response message";
            $this->view->message = $request->request_answer;
        }
    }
}