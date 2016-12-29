<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/23/2016
 * Time: 9:58 AM
 */
class Socialcommerce_Model_DbTable_Products extends Engine_Db_Table
{
    protected $_rowClass = 'Socialcommerce_Model_Product';

    public function getProductsPaginator($params = array())
    {
        return Zend_Paginator::factory($this->getProductsSelect($params));
    }

    public function getProductsSelect($params = array())
    {
        $productTbl = Engine_Api::_()->getDbTable('products', 'socialcommerce');
        $select = $productTbl->select();
        $select->setIntegrityCheck(false);

        if (isset($params['stall_id'])) {
            $select->where('stall_id = ?', $params['stall_id']);
        }

        if (!empty($params['keyword'])) {
            $select->where('title LIKE ?', '%'.$params['keyword'].'%');
        }

        if (isset($params['owner_id'])) {
            $select->where('owner_id = ?', $params['owner_id']);
        }

        if (!empty($params['category']) && is_numeric($params['category'])) {
            $select->where('category = ?', $params['category']);
        }

        if (!empty($params['limit'])) {
            $select->limit($params['limit']);
        }

        if (!empty($params['sort_by']))
        {
            switch ($params['sort_by']) {
                case 'most_liked':
                    $select->order('like_count DESC');
                    break;
                case 'most_viewed':
                    $select->order('view_count DESC');
                    break;
                case 'highest_sales':
                    $select->order('sold_qty DESC');
                    break;
                default:
                    $select->order('creation_date DESC');
                    break;
            }

        } else {
            $select->order('creation_date DESC');
        }

        $select->group("product_id");

        return $select;

    }

    public function getAllChildrenProductsByCategory($node)
    {
        $return_arr = array();
        $cur_arr = array();
        $list_categories = array();
        Engine_Api::_() -> getItemTable('socialcommerce_category') -> appendChildToTree($node, $list_categories);
        foreach($list_categories as $category)
        {
            $select = $this -> select() -> where('category = ?', $category -> category_id);
            $cur_arr = $this -> fetchAll($select);
            if(count($cur_arr) > 0)
            {
                $return_arr[] = $cur_arr;
            }
        }
        return $return_arr;
    }
}