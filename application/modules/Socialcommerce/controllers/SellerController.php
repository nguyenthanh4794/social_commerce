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
        if (!$this -> _helper -> requireUser -> isValid())
            return;
        $viewer = Engine_Api::_()->user()->getViewer();

        $account = Engine_Api::_() -> getDbTable('accounts', 'socialcommerce') -> getAccount();

        if($account && !Engine_Api::_()->core()->hasSubject('socialcommerce_account')) {
            Engine_Api::_() -> core() -> setSubject($account);
        }

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('socialcommerce_main', array(), 'socialcommerce_main_account');
    }

    public function infoAction()
    {
        $this->_helper->content
            ->setEnabled()
        ;

        if (!Engine_Api::_()->core()->hasSubject()) {
            return;
        }

        $this->view->account = $account = Engine_Api::_()->core()->getSubject('socialcommerce_account');
    }

    public function createAction()
    {
        $this->view->form = $form = new Socialcommerce_Form_SellerInfo();

        if( !$this->getRequest()->isPost() )
        {
            return;
        }

        if( !$form->isValid($this->getRequest()->getPost()) )
        {
            return;
        }

        $db = Engine_Api::_()->getItemTable('album')->getAdapter();
        $db->beginTransaction();

        try
        {
            $account = $form->postEntry();
            $db->commit();
        }
        catch( Exception $e )
        {
            $db->rollBack();
            throw $e;
        }
        return $this -> _forward('success', 'utility', 'core', array(
            'parentRedirect' => Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array(
                'module' => 'socialcommerce',
                'controller' => 'seller',
                'action' => 'dashboard',
                'account_id' => $account -> getIdentity(),
            ), 'socialcommerce_general', true),
            'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Your account has been successfully created.'))
        ));
    }

    public function editAction()
    {
        $account = Engine_Api::_()->core()->getSubject('socialcommerce_account');

        $this->view->form = $form = new Socialcommerce_Form_SellerInfo();
        $form->populate($account->toArray());
        $form->setTitle('Edit your seller information');
        $form->submit->setLabel('Save');

        if( !$this->getRequest()->isPost() )
        {
            return;
        }

        if( !$form->isValid($this->getRequest()->getPost()) )
        {
            return;
        }

        $db = Engine_Api::_()->getItemTable('album')->getAdapter();
        $db->beginTransaction();

        try
        {
            $account->setFromArray($form->getValues());
            $account->save();
            $db->commit();
        }
        catch( Exception $e )
        {
            $db->rollBack();
            throw $e;
        }
        return $this -> _forward('success', 'utility', 'core', array(
            'parentRedirect' => Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array(
                'module' => 'socialcommerce',
                'controller' => 'seller',
                'action' => 'info',
                'account_id' => $account -> getIdentity(),
            ), 'socialcommerce_general', true),
            'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Your account has been successfully updated.'))
        ));
    }

    public function manageListingsAction()
    {
        $this->_helper->content
            ->setEnabled()
        ;

        if (!Engine_Api::_()->core()->hasSubject()) {
            $this->view->hasAccount = false;
            return;
        }

        $this -> view -> form = $form = new Socialcommerce_Form_Seller_Search();
    }

    public function dashboardAction()
    {
        $this->_helper->content
            ->setEnabled()
        ;

        if (!$this -> _helper -> requireUser -> isValid())
            return;
        Zend_Registry::set('SELLERMENU_ACTIVE','dashboard');
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
        $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('orderItems', 'socialcommerce')->getOrderItemsPaginator($values);
        $this->view->params = $values;
        Zend_Registry::set('SELLERMENU_ACTIVE', 'buying-activities');
    }
}