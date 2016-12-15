<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/27/2016
 * Time: 9:22 PM
 */
class Socialcommerce_Model_Category extends Socialcommerce_Model_Node
{
    protected $_searchTriggers = false;
    protected $_parent_type = 'user';
    protected $_owner_type = 'user';
    protected $_type = 'socialcommerce_category';

    public function getParentCategoryLevel1()
    {
        $i = 1;
        $loop_item = $this;
        while($i < 4)
        {
            $item = $loop_item -> getParent($loop_item -> getIdentity());
            if(count($item->themes) > 0)
            {
                return $item;
            }
            $loop_item = $item;
            $i++;
        }
    }

    public function getParent($category_id = null)
    {
        $item = Engine_Api::_()->getItem('socialcommerce_category', $category_id);
        $parent_item = Engine_Api::_()->getItem('socialcommerce_category', $item->parent_id);
        return $parent_item;
    }

    public function getHref($params = array()) {
        $params = array_merge(array(
            'route' => 'socialcommerce_general',
            'controller' => 'index',
            'action' => 'browse',
            'category_id' => $this->getIdentity(),
        ), $params);
        $route = $params['route'];
        unset($params['route']);
        return Zend_Controller_Front::getInstance()->getRouter()
            ->assemble($params, $route, true);
    }

    public function getTable() {
        if(is_null($this -> _table)) {
            $this -> _table = Engine_Api::_() -> getDbtable('categories', 'socialcommerce');
        }
        return $this -> _table;
    }

    public function checkHasListing()
    {
        $table = Engine_Api::_() -> getDbTable('categorymaps', 'socialcommerce');
        $select = $table -> select() -> where('category_id = ?', $this->getIdentity()) -> limit(1);
        $row = $table -> fetchRow($select);
        if($row)
            return true;
        else {
            return false;
        }
    }

    public function getMoveCategoriesByLevel($level)
    {
        $table = Engine_Api::_() -> getDbtable('categories', 'socialcommerce');
        $select = $table -> select()
            -> where('category_id <>  ?', 1) // not default
            -> where('category_id <>  ?', $this->getIdentity())// not itseft
            -> where('level = ?', $level);
        $result = $table -> fetchAll($select);
        return $result;
    }

    public function setTitle($newTitle) {
        $this -> title = $newTitle;
        $this -> save();
        return $this;
    }

    public function shortTitle() {
        return strlen($this -> title) > 20 ? (substr($this -> title, 0, 17) . '...') : $this -> title;
    }

    public function checkHasProducts()
    {
        $list_categories = array();
        $table = Engine_Api::_() -> getItemTable('socialcommerce_product');
        Engine_Api::_()->getItemTable('socialcommerce_category') -> appendChildToTree($this, $list_categories);
        foreach($list_categories as $category)
        {
            $select = $table -> select() -> where('category = ?', $category -> category_id) -> limit(1);
            $row = $table -> fetchRow($select);
            if($row)
                return $category -> category_id;
        }
        return false;
    }

    public function getCategoryParent()
    {
        $parent_item = Engine_Api::_()->getItem('socialcommerce_category', $this->parent_id);
        return $parent_item;
    }

    public function getChildList() {
        $table = Engine_Api::_()->getItemTable('socialcommerce_category');
        $select = $table->select();
        $select->where('parent_id = ?', $this->getIdentity());
        $childList = $table->fetchAll($select);
        return $childList;
    }

    public function getNumOfListings() {
        $table = Engine_Api::_()->getDbTable('products', 'socialcommerce');
        $select = $table->getProductsSelect(array('category'=>$this->getIdentity()));
        $rows = $table->fetchAll($select);
        return count($rows);
    }

    public function getTitle() {
        $view = Zend_Registry::get('Zend_View');
        return $view->translate($this->title);
    }
}