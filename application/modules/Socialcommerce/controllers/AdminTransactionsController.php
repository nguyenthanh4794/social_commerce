<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/16/2016
 * Time: 7:28 AM
 */
class Socialcommerce_AdminTransactionsController extends Core_Controller_Action_Admin
{
    public function init() {
        $this -> view -> navigation = $navigation = Engine_Api::_() -> getApi('menus', 'core') -> getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_main_transactions');
    }

    public function indexAction()
    {
        $page = $this->_getParam('page',1);
        $this->view->form = $form = new Socialcommerce_Form_Admin_Transactions_Search();
        $values = array();
        if ($form->isValid($this->_getAllParams())) {
            $values = $form->getValues();
        }
        $limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('store.page', 10);
        $values['limit'] = $limit;
        $viewer = Engine_Api::_()->user()->getViewer();
        $this->view->viewer = $viewer;
        $this->view->paginator = Socialcommerce_Api_Transaction::getInstance()->getTransactionsPaginator($values);

        $this->view->paginator->setCurrentPageNumber($page);
        $this->view->formValues = $values;
    }
}