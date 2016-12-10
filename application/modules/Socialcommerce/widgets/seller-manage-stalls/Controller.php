<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/12/2016
 * Time: 11:33 PM
 */
class Socialcommerce_Widget_SellerManageStallsController extends Engine_Content_Widget_Abstract
{
    protected $_childCount;

    public function indexAction()
    {
        // Return if guest try to access to create link.
        $viewer = Engine_Api::_()->user()->getViewer();
        $paginator = Engine_Api::_()->getDbTable('stalls', 'socialcommerce')->getStallsPaginator(array('owner_id' => $viewer->getIdentity()));
        $this->view->paginator = $paginator;
        $this->_childCount = $paginator->getTotalItemCount();
    }

    public function getChildCount()
    {
        return $this->_childCount;
    }
}