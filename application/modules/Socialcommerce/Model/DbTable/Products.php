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

        $select->group("product_id");
        $select->order('product_id DESC');
        return $select;

    }
}