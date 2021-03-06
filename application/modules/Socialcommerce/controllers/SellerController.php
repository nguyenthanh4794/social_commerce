<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/30/2016
 * Time: 10:57 AM
 */
class Socialcommerce_SellerController extends Core_Controller_Action_Standard
{
    public function init()
    {
        if (!$this->_helper->requireUser->isValid())
            return;
        $viewer = Engine_Api::_()->user()->getViewer();

        $account = Engine_Api::_()->getDbTable('accounts', 'socialcommerce')->getAccount();

        if ($account && !Engine_Api::_()->core()->hasSubject('socialcommerce_account')) {
            Engine_Api::_()->core()->setSubject($account);
        }

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('socialcommerce_main', array(), 'socialcommerce_main_account');
    }

    public function infoAction()
    {
        $this->_helper->content
            ->setEnabled();

        if (!Engine_Api::_()->core()->hasSubject()) {
            return;
        }

        $this->view->account = $account = Engine_Api::_()->core()->getSubject('socialcommerce_account');
    }

    public function createAction()
    {
        $this->view->form = $form = new Socialcommerce_Form_SellerInfo();

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        $db = Engine_Api::_()->getItemTable('album')->getAdapter();
        $db->beginTransaction();

        try {
            $account = $form->postEntry();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        return $this->_forward('success', 'utility', 'core', array(
            'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
                'module' => 'socialcommerce',
                'controller' => 'seller',
                'action' => 'dashboard',
                'account_id' => $account->getIdentity(),
            ), 'socialcommerce_general', true),
            'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your account has been successfully created.'))
        ));
    }

    public function editAction()
    {
        $account = Engine_Api::_()->core()->getSubject('socialcommerce_account');

        $this->view->form = $form = new Socialcommerce_Form_SellerInfo();
        $form->populate($account->toArray());
        $form->setTitle('Edit your seller information');
        $form->submit->setLabel('Save');

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        $db = Engine_Api::_()->getItemTable('album')->getAdapter();
        $db->beginTransaction();

        try {
            $account->setFromArray($form->getValues());
            $account->save();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        return $this->_forward('success', 'utility', 'core', array(
            'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
                'module' => 'socialcommerce',
                'controller' => 'seller',
                'action' => 'info',
                'account_id' => $account->getIdentity(),
            ), 'socialcommerce_general', true),
            'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your account has been successfully updated.'))
        ));
    }

    public function manageListingsAction()
    {
        $this->_helper->content
            ->setEnabled();

        if (!Engine_Api::_()->core()->hasSubject()) {
            $this->view->hasAccount = false;
            return;
        }

        $this->view->form = $form = new Socialcommerce_Form_Seller_Search();
    }

    public function dashboardAction()
    {
        $this->_helper->content
            ->setEnabled();

        if (!$this->_helper->requireUser->isValid())
            return;
        Zend_Registry::set('SELLERMENU_ACTIVE', 'dashboard');
    }

    public function buyingActivitiesAction()
    {
        if (!$this->_helper->requireUser()->isValid())
            return;
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

        $this->view->form = $form = new Socialcommerce_Form_Seller_SearchBuyActivities();
        $page = $this->_getParam('page', 1);

        $values = $this->getRequest()->getParams();

        if (!$form->isValid($values))
            return;
        $values = $form->getValues();
        $values['limit'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('store.page', 10);
        $values['page'] = $page;
        $values['owner_id'] = $viewer->getIdentity();

        $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('orderItems', 'socialcommerce')->getOrderItemsPaginator($values);
        $this->view->params = $values;

        $this->_helper->content
            ->setEnabled();
    }

    public function receivedAction()
    {
        // In smoothbox
        $this -> _helper -> layout -> setLayout('admin-simple');

        if (!$this->_helper->requireUser()->isValid())
            return;

        $orderItem_id = $this->_getParam('orderItem_id');

        $this -> view -> orderItem_id = $orderItem_id;

        // Check post
        if ($this -> getRequest() -> isPost()) {
            $table = new Socialcommerce_Model_DbTable_OrderItems();
            $orderItem = $table->find($orderItem_id)->current();
            $orderItem->updateStatus('delivered');

            return $this -> _forward('success', 'utility', 'core', array(
                'layout' => 'default-simple',
                'parentRefresh' => true,
                'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Your order has been successfully updated.'))
            ));
        }

        // Output
        $this -> _helper -> layout -> setLayout('default-simple');
        $this -> renderScript('seller/confirm.tpl');

    }
    public function shippedAction()
    {
        // In smoothbox
        $this -> _helper -> layout -> setLayout('admin-simple');

        if (!$this->_helper->requireUser()->isValid())
            return;

        $orderItem_id = $this->_getParam('orderItem_id');

        $this -> view -> orderItem_id = $orderItem_id;

        // Check post
        if ($this -> getRequest() -> isPost()) {
            $table = new Socialcommerce_Model_DbTable_OrderItems();
            $orderItem = $table->find($orderItem_id)->current();
            $orderItem->updateStatus('shipped');

            return $this -> _forward('success', 'utility', 'core', array(
                'layout' => 'default-simple',
                'parentRefresh' => true,
                'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Your order has been successfully updated.'))
            ));
        }

        // Output
        $this -> _helper -> layout -> setLayout('default-simple');
        $this -> renderScript('seller/confirm-shipped.tpl');

    }

    public function printAction()
    {
        $order_id = $this->_getParam('order_id');
        $orderTable = Engine_Api::_()->getDbTable('orders', 'socialcommerce');
        $order = $orderTable->getByOrderId($order_id);
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

    public function paymentAction()
    {
        if (!$this->_helper->requireUser()->isValid()) return;

        $this->_helper->content
            ->setEnabled();

        $viewer = Engine_Api::_()->user()->getViewer();

        $Table =  Engine_Api::_()->getDbTable('paypalaccounts', 'socialcommerce');
        $select =  $Table->select()->where('owner_id = ? ', $viewer->getIdentity());
        $this->view->account = $item =  $Table->fetchRow($select);
    }

    public function createPaypalAccountAction()
    {
        // only members can create account
        if (!$this->_helper->requireUser()->isValid()) return;
        $bIsEdit = false;

        if ($this->_getParam('id') > 0)
        {
            $bIsEdit = $this->_getParam('id');
        }

        $table = Engine_Api::_()->getDbTable('paypalaccounts', 'socialcommerce');

        $viewer = Engine_Api::_()->user()->getViewer();

        $this->view->form = $form = new Socialcommerce_Form_Account_CreatePayPalAccount();

        if ($bIsEdit) {
            $form->setTitle('Edit PayPal Account');
            $form->submit->setLabel('Save changes');
            $table =  Engine_Api::_()->getDbTable('paypalaccounts', 'socialcommerce');
            $paypalAccount =  $table->find($bIsEdit)->current();
            $form->populate($paypalAccount->toArray());
        }

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $db = $table->getAdapter();
            $db->beginTransaction();
            try {
                if (!$bIsEdit)
                    $paypalAccount = $table->createRow();

                $paypalAccount->setFromArray($form->getValues());
                $paypalAccount->owner_id = $viewer->getIdentity();
                $paypalAccount->save();

                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }

            return $this -> _forward('success', 'utility', 'core', array(
                'parentRedirect' => Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array(
                    'controller' => 'seller',
                    'action' => 'payment',
                ), 'socialcommerce_general', true),
                'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Your PayPal account has been successfully created.'))
            ));
        }
    }
}