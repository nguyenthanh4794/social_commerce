<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/3/2016
 * Time: 10:17 AM
 */
class Socialcommerce_Widget_StallProfileSalesController extends Engine_Content_Widget_Abstract
{
    protected $_childCount;
    public function indexAction()
    {
        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->setNoRender();
        }

        // Get subject and check auth
        $subject = Engine_Api::_()->core()->getSubject('socialcommerce_stall');
        $this-> view -> viewer = $viewer = Engine_Api::_()->user()->getViewer();

        if (!$viewer->isSelf($subject->getOwner())) {
            return $this->setNoRender();
        }

        $values['stall_id'] = $subject->getIdentity();

        $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('orderItems', 'socialcommerce')->getOrderItemsPaginator($values);
        $this->_childCount = $paginator->getTotalItemCount();
    }

    public function getChildCount()
    {
        return $this->_childCount;
    }
}