<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/27/2016
 * Time: 9:17 PM
 */
class Socialcommerce_AdminCategoriesController extends Core_Controller_Action_Admin
{
    protected $_paginate_params = array();
    public function init() {
        $this -> view -> navigation = $navigation = Engine_Api::_() -> getApi('menus', 'core') -> getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_main_categories');
    }

    public function getDbTable() {
        return Engine_Api::_() -> getDbTable('categories', 'socialcommerce');
    }

    public function indexAction() {
        $table = $this -> getDbTable();
        $node = $table -> getNode($this -> _getParam('parent_id', 0));
        if($node -> option_id == 1)
        {
            try{
                $option = Engine_Api::_() -> fields() -> getOption($node -> option_id, 'socialcommerce_listing');
            }
            catch(exception $e)
            {
            }
            if(empty($option))
            {
                Engine_Api::_()->getApi('core', 'socialcommerce')->typeCreate('Default field');
            }
        }
        $this -> view -> categories = $node -> getChilren();
        $this -> view -> category = $node;
    }

    public function addCategoryAction() {
        // In smoothbox
        $this -> _helper -> layout -> setLayout('admin-simple');

        // Generate and assign form
        $parentId = $this -> _getParam('parent_id', 0);
        $form = $this -> view -> form = new Socialcommerce_Form_Admin_Category();
        $form -> setAction($this -> getFrontController() -> getRouter() -> assemble(array()));
        if ($parentId != '1') {
            $form -> removeElement('themes');
            $form -> removeElement('photo');
        }
        $table = $this -> getDbTable();
        $node = $table -> getNode($parentId);
        //maximum 4 level category
        if ($node -> level > 3) {
            throw new Zend_Exception('Maximum 4 levels of category.');
        }
        // Check post
        if ($this -> getRequest() -> isPost() && $form -> isValid($this -> getRequest() -> getPost())) {
            // we will add the category
            $values = $form -> getValues();
            $user = Engine_Api::_() -> user() -> getViewer();
            $data = array('user_id' => $user -> getIdentity(), 'title' => $values["label"], 'themes' => $values["themes"]);
            if (!empty($values['photo'])) {
                $data['photo'] = $form -> photo;
            }
            $table -> addChild($node, $data);
            $this -> _forward('success', 'utility', 'core', array('smoothboxClose' => 10, 'parentRefresh' => 10, 'messages' => array('')));
        }

        // Output
        $this -> renderScript('admin-categories/form.tpl');
    }

}