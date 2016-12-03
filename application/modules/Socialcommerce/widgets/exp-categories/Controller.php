<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/26/2016
 * Time: 8:11 PM
 */
class Socialcommerce_Widget_ExpCategoriesController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        $categoryTable = Engine_Api::_()->getDbTable('categories', 'socialcommerce');
        $categories = $categoryTable->getAllCategoriesByParent();

        $this->view->categories = $categories;
        $this -> view -> inHomePage = $inHomePage;
    }
}