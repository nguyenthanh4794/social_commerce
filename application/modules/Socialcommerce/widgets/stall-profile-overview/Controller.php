<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/22/2016
 * Time: 10:45 PM
 */
class Socialcommerce_Widget_StallProfileOverviewController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        $viewer = Engine_Api::_()->user()->getViewer();

        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->setNoRender();
        }

        // Get subject and check auth
        $subject = Engine_Api::_()->core()->getSubject('socialcommerce_stall');

        $this->view->stall = $stall = $subject;
    }
}