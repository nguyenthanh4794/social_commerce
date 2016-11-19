<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 6:32 PM
 */
class Socialcommerce_FaqsController extends Core_Controller_Action_Standard
{
    public function init()
    {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('socialcommerce_main', array(), 'socialcommerce_main_faqs');
    }

    public function indexAction()
    {
        $tableFAQs = Engine_Api::_()->getDbTable('faqs', 'socialcommerce');
        $select = $tableFAQs->select()->where('status = ?', 1)->order('ordering asc');
        $this->view->items = $items = $tableFAQs->fetchAll($select);
    }
}