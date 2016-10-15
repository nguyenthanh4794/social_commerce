<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/14/2016
 * Time: 9:20 PM
 */
class Socialcommerce_Widget_MainMenuController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        // Get navigation
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('socialcommerce_main');
        if( count($this->view->navigation) == 1 ) {
            $this->view->navigation = null;
        }
    }
}