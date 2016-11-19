<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/17/2016
 * Time: 10:07 PM
 */
class Socialcommerce_AdminManageListingsController extends Core_Controller_Action_Admin
{
    public function init()
    {
        parent::init();
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_main_manage-listings');
    }

    public function getDbTable()
    {
        return Engine_Api::_() -> getDbTable('products', 'socialcommerce');
    }

    public function indexAction()
    {
        if ($this -> getRequest() -> isPost()) {
            $values = $this -> getRequest() -> getPost();

            if (!empty($values['submit']) && in_array($values['submit'], array('delete', 'approve', 'deny', 'feature', 'unfeature'))) {
                foreach ($values as $key => $value) {
                    if ($key == 'item_' . $value) {
                        $item = Engine_Api::_() -> getItem('socialcommerce_product', $value);
                        if ($item)
                            $item -> $values['submit']();
                    }
                }
            }
        }

        $params = $this -> _getAllParams();
        $params['valid_form'] = true;
        $table = $this -> getDbTable();
        $this -> view -> paginator = $paginator = $table -> getProductsPaginator($params);

        $this -> view -> paginator -> setItemCountPerPage(10);
        $page = $this -> _getParam('page', 1);
        $this -> view -> paginator -> setCurrentPageNumber($page);
        // Form Search Form
        $this -> view -> form = $form = new Socialcommerce_Form_Admin_Product_Search();

        $form -> populate($params);
        $formValues = $form -> getValues();
        if (isset($params['fieldOrder'])) {
            $formValues['fieldOrder'] = $params['fieldOrder'];
        }
        if (isset($params['direction'])) {
            $formValues['direction'] = $params['direction'];
        }
        $this -> view -> params = $formValues;
    }

    public function multiDeleteConfirmAction()
    {
        $this -> _helper -> layout -> setLayout('default-simple');

        if ($this -> getRequest() -> isPost()) {
            $this -> view -> closeSmoothbox = true;
        }

        // Ouput
        $this -> renderScript('admin-manage-listings/multidelete-confirm.tpl');
    }
}