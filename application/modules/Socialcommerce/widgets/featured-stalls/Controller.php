<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/3/2016
 * Time: 11:02 PM
 */
class Socialcommerce_Widget_FeaturedStallsController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        $params['featured'] = 1;
        $paginator = Engine_Api::_() -> getDbTable('stalls', 'socialcommerce') -> getStallsPaginator($params);
//        $paginator -> setItemCountPerPage($this -> _getParam('itemCountPerPage', 10));
        $this -> view -> view_mode = 1;
        $this -> view -> paginator = $paginator;
    }
}