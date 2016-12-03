<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/1/2016
 * Time: 10:13 PM
 */
class Socialcommerce_Widget_SellerManageListingsController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        // Return if guest try to access to create link.
        $viewer = Engine_Api::_()->user()->getViewer();
        $paginator = Engine_Api::_()->getDbTable('products', 'socialcommerce')->getProductsPaginator(array('owner_id' => $viewer->getIdentity()));
        $this->view->paginator = $paginator;
    }
}