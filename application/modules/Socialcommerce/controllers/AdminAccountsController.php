<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 5:06 PM
 */
class Socialcommerce_AdminAccountsController extends Core_Controller_Action_Admin
{
    public function init() {
        $this -> view -> navigation = $navigation = Engine_Api::_() -> getApi('menus', 'core') -> getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_main_accounts');
    }

    public function indexAction()
    {
        $page = $this->_getParam('page', 1);

        $params = array('user_name' => $this->_getParam('name'), 'page' => $page);

        $this->view->viewer = Engine_Api::_()->user()->getViewer();

        $this->view->paginator = Engine_Api::_()->getDbTable('accounts', 'socialcommerce')->getAccountsPaginator($params);
        $this->view->formValues = $params;
    }
}