<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/30/2016
 * Time: 11:54 AM
 */
class Socialcommerce_Widget_SellerMenuController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        // Don't render this if not logged in
        $viewer = Engine_Api::_()->user()->getViewer();
        if( !$viewer->getIdentity() ) {
            return $this->setNoRender();
        }

        $this->view->navigation = Engine_Api::_()
            ->getApi('menus', 'core')
            ->getNavigation('socialcommerce_seller');
    }

    public function getCacheKey()
    {
        $viewer = Engine_Api::_()->user()->getViewer();
        $translate = Zend_Registry::get('Zend_Translate');
        return $viewer->getIdentity() . $translate->getLocale();
    }
}