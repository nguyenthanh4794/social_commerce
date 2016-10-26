<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/26/2016
 * Time: 8:11 PM
 */
class Socialcommerce_Widget_ProductsListingController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        $viewer = Engine_Api::_()->user()->getViewer();

        $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('products', 'socialcommerce') -> getProductsPaginator(array());

        // Set item count per page and current page number
        $paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));

        $this -> view -> canCreate = true;
    }
}