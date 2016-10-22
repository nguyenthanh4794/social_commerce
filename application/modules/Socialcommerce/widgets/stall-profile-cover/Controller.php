<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/22/2016
 * Time: 9:46 PM
 */
class Socialcommerce_Widget_StallProfileCoverController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->setNoRender();
        }

        // Get subject and check auth
        $subject = Engine_Api::_()->core()->getSubject('socialcommerce_stall');

        $view = $this->view;

        $this->view->stall = $stall = $subject;

        $category = null;

        if ($stall->category)
        {
            $category = Engine_Api::_()->getItem('socialcommerce_category', $stall->category);
        }

        $this->view->category = $category;
        $this->view->user = $user = $stall->getOwner();
        $this->view->canComment = $canComment = $stall->authorization()->isAllowed($viewer, 'comment');
    }
}