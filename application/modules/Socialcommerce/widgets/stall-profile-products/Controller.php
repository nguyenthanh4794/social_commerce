<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/23/2016
 * Time: 1:12 AM
 */
class Socialcommerce_Widget_StallProfileProductsController extends Engine_Content_Widget_Abstract
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
    }
}