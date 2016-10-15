<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/27/2016
 * Time: 9:56 PM
 */
class Socialcommerce_AdminCategoriesFieldsController extends Fields_Controller_AdminAbstract
{
    protected $_fieldType = 'socialcommerce_listing';

    protected $_requireProfileType = true;

    public function indexAction()
    {
        // Make navigation
        $this -> view -> navigation = $navigation = Engine_Api::_() -> getApi('menus', 'core') -> getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_main_categories');
        $option_id =  $this->_getParam('option_id');
        $tableCategory = Engine_Api::_()->getItemTable('socialcommerce_category');
        $category = $tableCategory -> getCategoryByOptionId($option_id);
        $this -> view -> category = $category;
        parent::indexAction();
    }

    public function headingCreateAction()
    {
        parent::headingCreateAction();
        $form = $this->view->form;
        if($form){
            $form -> removeElement('show');
            $display = $form->getElement('display');
            $display->setLabel('Show on listing page?');
            $display->setOptions(array('multiOptions' => array(
                1 => 'Show on listing page',
                0 => 'Hide on listing page'
            )));
        }
    }

    public function headingEditAction()
    {
        parent::headingEditAction();
        $form = $this->view->form;
        if($form){
            $form -> removeElement('show');
            $display = $form->getElement('display');
            $display->setLabel('Show on listing page?');
            $display->setOptions(array('multiOptions' => array(
                1 => 'Show on listing page',
                0 => 'Hide on listing page'
            )));
        }
    }
    public function fieldCreateAction(){
        parent::fieldCreateAction();
        // remove stuff only relavent to profile questions
        $form = $this->view->form;

        if($form){
            $form -> removeElement('show');

            $display = $form->getElement('search');
            $display->setLabel('Show on browse search?');
            $display->setOptions(array('multiOptions' => array(
                0 => 'Hide on browse search',
                1 => 'Show on browse search',
                2 => 'Show on browse search when no question has been selected',
            )));

            $display = $form->getElement('display');
            $display->setLabel('Show on listing page?');
            $display->setOptions(array('multiOptions' => array(
                1 => 'Show on listing page',
                0 => 'Hide on listing page'
            )));
        }
    }

    public function fieldEditAction(){
        parent::fieldEditAction();
        // remove stuff only relavent to profile questions
        $form = $this->view->form;

        if($form){
            $form -> removeElement('show');

            $display = $form->getElement('search');
            $display->setLabel('Show on browse search?');
            $display->setOptions(array('multiOptions' => array(
                0 => 'Hide on browse search',
                1 => 'Show on browse search',
                2 => 'Show on browse search when no question has been selected',
            )));

            $display = $form->getElement('display');
            $display->setLabel('Show on listing page?');
            $display->setOptions(array('multiOptions' => array(
                1 => 'Show on listing page',
                0 => 'Hide on listing page'
            )));
        }
    }
}