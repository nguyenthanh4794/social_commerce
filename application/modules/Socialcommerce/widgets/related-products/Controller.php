<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/29/2016
 * Time: 12:42 AM
 */
class Socialcommerce_Widget_RelatedProductsController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        return $this->setNoRender();
        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->setNoRender();
        }

        // Get subject and check auth
        $subject = Engine_Api::_()->core()->getSubject('socialcommerce_product');

        $limit = $this -> _getParam('itemCountPerPage', 12);
        $values['limit'] = $limit;
        $values['category'] = $subject->category;

        $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('products', 'socialcommerce') -> getProductsPaginator($values);

        // Set item count per page and current page number
        $paginator->setItemCountPerPage($limit);

        $this -> view -> canCreate = true;
    }
}