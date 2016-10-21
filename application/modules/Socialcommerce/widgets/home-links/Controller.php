<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/21/2016
 * Time: 8:56 PM
 */
class Socialcommerce_Widget_HomeLinksController extends Engine_Content_Widget_Abstract
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
            ->getNavigation('socialcommerce_link');
    }

    public function getCacheKey()
    {
        $viewer = Engine_Api::_()->user()->getViewer();
        $translate = Zend_Registry::get('Zend_Translate');
        return $viewer->getIdentity() . $translate->getLocale();
    }
}