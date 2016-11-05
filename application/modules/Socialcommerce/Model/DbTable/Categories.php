<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/27/2016
 * Time: 9:19 PM
 */
class Socialcommerce_Model_DbTable_Categories extends Socialcommerce_Model_DbTable_Nodes
{
    protected $_rowClass = 'Socialcommerce_Model_Category';

    public function getCategoriesAssoc($level = 0)
    {
        if ($level) {
            $stmt = $this->select()
                ->from($this, array('category_id', 'title'))
                ->where('level = ?', $level)
                ->order('order ASC')
                ->query();
        } else {
            $stmt = $this->select()
                ->from($this, array('category_id', 'title'))
                ->order('order ASC')
                ->query();
        }

        $data = array();
        foreach( $stmt->fetchAll() as $category ) {
            $data[$category['category_id']] = $category['title'];
        }

        return $data;
    }

    public function getAllCategoriesByParent($parent_id = 1)
    {
        $aCategories = $this->select()
            ->from($this, array('category_id', 'title'))
            ->where('parent_id = ?', $parent_id)
            ->order('order ASC')
            ->query()->fetchAll();

        foreach( $aCategories as $iKey => $category ) {
            $aCategories[$iKey]['link'] = $this->getHref($category['category_id'], $category['title']);
            $aCategories[$iKey]['sub_categories'] = $this->getAllCategoriesByParent($category['category_id']);
            $class_category_item = str_replace(' ', '_', strtolower($aCategories[$iKey]['title']));
            $aCategories[$iKey]['class_category_item'] = $class_category_item;
        }

        return $aCategories;
    }

    public function getHref($iCategoryId, $sTitle) {

        $params = array(
            'route' => 'socialcommerce_category',
            'controller' => 'index',
            'action' => 'listings',
            'category_id' => $iCategoryId,
            'slug' => $this->seoUrl($sTitle),
        );

        $route = $params['route'];
        unset($params['route']);
        unset($params['type']);
        return Zend_Controller_Front::getInstance()->getRouter()
            ->assemble($params, $route, true);
    }

    function seoUrl($string)
    {
        $string = strtolower($string);
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

        public function getCategoryByOptionId($option_id)
    {
        $select = $this->select();
        $select -> where('option_id = ?', $option_id);
        $select -> limit(1);
        $item  = $this->fetchRow($select);
        return $item;
    }

    public function getFirstCategory()
    {
        $select = $this->select();
        $select -> order('category_id ASC');
        $select -> limit(2);
        $select -> where('category_id <> 1');
        $item  = $this->fetchRow($select);
        return $item;
    }

    public function deleteNode(Socialcommerce_Model_Node $node, $node_id = NULL) {
        parent::deleteNode($node);
    }

    public function getCategories() {
        $table = Engine_Api::_() -> getDbTable('categories', 'socialcommerce');
        $tree = array();
        $node = $table -> getNode(1);
        $this->appendChildToTree($node, $tree);
        return $tree;
    }

    public function appendChildToTree($node, &$tree) {
        array_push($tree, $node);
        $children = $node->getChilren();
        foreach ($children as $child_node) {
            $this->appendChildToTree($child_node, $tree);
        }
    }

    public function getAllCategories()
    {
        $select = $this -> select() -> order('title') -> where('category_id <> 1');
        return $this -> fetchAll($select);
    }
}