<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/13/2016
 * Time: 1:29 AM
 */
class Socialcommerce_Widget_SellerInformationController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this -> setNoRender();
        }

        $this->view->account = $account = Engine_Api::_()->core()->getSubject('socialcommerce_account');
    }
}