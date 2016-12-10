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

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $location = $request -> getParam('location', '');
        $form = new Socialcommerce_Form_Search(array('location' => $location));
        $form->populate($request->getParams());

        $values = $form->getValues();
        $this->view->formValues = array_filter($values);

        $page = $request->getParam('page', 1);
        $limit = $this -> _getParam('itemCountPerPage', 12);
        $values['page'] = $page;
        $values['limit'] = $limit;

        if ($controller == 'index' && $action == 'browse') {
            $this->view->inBrowsePage = true;
        }

        if ($controller == 'product' && $action == 'browse') {
            $this->view->pager = true;
        }

        $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('products', 'socialcommerce') -> getProductsPaginator($values);

        // Set item count per page and current page number
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($page);

        $this -> view -> canCreate = true;
    }
}