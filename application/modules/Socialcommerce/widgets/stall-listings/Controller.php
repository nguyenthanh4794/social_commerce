<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/15/2016
 * Time: 4:01 PM
 */
class Socialcommerce_Widget_StallListingsController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        $params = $this -> _getAllParams();
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $form = new Socialcommerce_Form_Search(array(
            'type' => 'socialcommerce_listing',
            'location' => $params['location'],
        ));

        if($form->isValid($params)) {
            $values = $form->getValues();
        } else {
            $values = array();
        }

        $page = $values['page'];
        if (!$page) $page = 1;
        $paginator = Engine_Api::_() -> getDbTable('stalls', 'socialcommerce') -> getStallsPaginator($values);
        $paginator -> setCurrentPageNumber($page);
        $paginator -> setItemCountPerPage($this -> _getParam('itemCountPerPage', 10));
        $this -> view -> paginator = $paginator;
    }
}