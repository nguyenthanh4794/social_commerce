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
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $params = $request->getParams();

        $form = new Socialcommerce_Form_Search(array(
            'location' => $params['location'],
        ));

        $controller = $request->getControllerName();
        $action = $request->getActionName();

        if ($controller == 'index' && $action == 'browse')
            $this->view->inBrowsePage = true;

        if($form->isValid($params)) {
            $values = $form->getValues();
            $this->view->formValues = array_filter($values);
        } else {
            $values = array();
        }

        $page = $request->getParam('page', 1);
        $limit = $this -> _getParam('itemCountPerPage', 4);

        $paginator = Engine_Api::_() -> getDbTable('stalls', 'socialcommerce') -> getStallsPaginator($values);
        if (in_array($controller, array('stall'))) {
            $this->view->pager = true;
            $limit = 6;
        }

        $paginator -> setCurrentPageNumber($page);
        $paginator -> setItemCountPerPage($limit);
        $this -> view -> paginator = $paginator;
    }
}