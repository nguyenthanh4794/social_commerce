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