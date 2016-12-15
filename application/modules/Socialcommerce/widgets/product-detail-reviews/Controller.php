<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/15/2016
 * Time: 8:13 AM
 */
class Socialcommerce_Widget_ProductDetailReviewsController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        $viewer = Engine_Api::_()->user()->getViewer();

        if (!$viewer->getIdentity())
            return $this->setNoRender();
    }
}