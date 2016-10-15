<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/15/2016
 * Time: 10:27 AM
 */
class Socialcommerce_Model_DbTable_Stalls extends Engine_Db_Table
{
    protected $_rowClass = 'Socialcommerce_Model_Stall';

    public function getStallsPaginator($params = array())
    {
        return Zend_Paginator::factory($this->getStallsSelect($params));
    }

    public function getStallsSelect($params = array())
    {
        $stallTbl = Engine_Api::_()->getDbTable('stalls', 'socialcommerce');
        $stallTblName = $stallTbl->info('name');

        $searchTable = Engine_Api::_()->fields()->getTable('socialcommerce_listing', 'search');
        $searchTableName = $searchTable->info('name');

        $userTbl = Engine_Api::_()->getDbtable('users', 'user');
        $userTblName = $userTbl->info('name');

        $categoryTbl = Engine_Api::_()->getDbTable('categories', 'socialcommerce');
        $categoryTblName = $categoryTbl->info('name');

        $target_distance = $base_lat = $base_lng = "";
        if (isset($params['lat'])) {
            $base_lat = $params['lat'];
        }
        if (isset($params['long'])) {
            $base_lng = $params['long'];
        }
        //Get target distance in miles
        if (isset($params['within'])) {
            $target_distance = $params['within'];
        }

        $select = $stallTbl->select();
        $select->setIntegrityCheck(false);

        if ($base_lat && $base_lng && $target_distance && is_numeric($target_distance)) {
            $select->from("$stallTblName as stall", new Zend_Db_Expr("stall.*, ( 3959 * acos( cos( radians('$base_lat')) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$base_lng') ) + sin( radians('$base_lat') ) * sin( radians( latitude ) ) ) ) AS distance"));
            $select->where("latitude <> ''");
            $select->where("longitude <> ''");
        } else {
            $select->from("$stallTblName as stall", new Zend_Db_Expr("stall.*"));
        }
        $select
            ->joinLeft("$userTblName as user", "user.user_id = stall.owner_id", "")
            ->joinLeft("$categoryTblName as category", "category.category_id = stall.category", "")
            ->joinLeft("$searchTableName as search", "search.item_id = stall.stall_id", "");

        $select->group("stall.stall_id");

        $tmp = array();
        foreach ($params as $k => $v) {
            if (null == $v || '' == $v || (is_array($v) && count(array_filter($v)) == 0)) {
                continue;
            } else if (false !== strpos($k, '_field_')) {
                list($null, $field) = explode('_field_', $k);
                $tmp['field_' . $field] = $v;
            } else if (false !== strpos($k, '_alias_')) {
                list($null, $alias) = explode('_alias_', $k);
                $tmp[$alias] = $v;
            } else {
                $tmp[$k] = $v;
            }
        }
        $params = $tmp;

        if (isset($params['displayname']) && $params['displayname'] != '') {
            $select->where('stall.title LIKE ?', '%' . $params['displayname'] . '%');
        }

        if (isset($params['category']) && $params['category'] != 'all') {
            $node = $categoryTbl->getNode($params['category']);
            if ($node) {
                $tree = array();
                $categoryTbl->appendChildToTree($node, $tree);
                $categories = array();
                foreach ($tree as $node) {
                    array_push($categories, $node->category_id);
                }
                $select->where('stall.category IN (?)', $categories);
            }
        }

        if (isset($params['status']) && $params['status'] != 'all') {
            $select->where('stall.status = ?', $params['status']);
        }
        if (isset($params['featured']) && $params['featured'] != 'all') {
            $select->where('stall.featured = ?', $params['featured']);
        }

//        if (isset($params['user_id'])) {
//            $select->where('stall.user_id = ?', $params['user_id']);
//        } else {
//            if ($params['admin'] == null) {
//                $select
//                    ->where('stall.search = ?', 1)
//                    ->where('stall.status = ?', 'open')
//                    ->where('stall.approved_status = ?', 'approved');
//            }
//        }

        $searchParts = Engine_Api::_()->fields()->getSearchQuery('socialcommerce_listing', $params);
        foreach ($searchParts as $k => $v) {
            $select->where("search.$k", $v);
        }

        if ($base_lat && $base_lng && $target_distance && is_numeric($target_distance)) {
            $select->having("distance <= $target_distance");
            $select->order("distance ASC");
        }

        return $select;
    }
}