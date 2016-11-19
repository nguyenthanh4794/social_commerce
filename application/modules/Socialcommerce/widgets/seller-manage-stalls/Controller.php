<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/12/2016
 * Time: 11:33 PM
 */
class Socialcommerce_Widget_SellerManageStallsController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        // Return if guest try to access to create link.
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $viewer = Engine_Api::_()->user()->getViewer();
        $paginator = Engine_Api::_()->getDbTable('stalls', 'socialcommerce')->getStallsPaginator(array('owner_id' => $viewer->getIdentity()));
        $this->view->paginator = $paginator;
    }
}