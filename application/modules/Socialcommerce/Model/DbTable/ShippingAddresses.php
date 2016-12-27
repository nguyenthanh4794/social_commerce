<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/8/2016
 * Time: 8:13 PM
 */
class Socialcommerce_Model_DbTable_ShippingAddresses extends Engine_Db_Table
{
    protected $_name = 'socialcommerce_shippingaddresses';
    protected $_rowClass = 'Socialcommerce_Model_ShippingAddress';

    public function getShippingInfosByUserId($user_id = 0)
    {
        if (!$user_id || !is_numeric($user_id)) {
            return array();
        }

        $select = $this->select()->where('user_id = ?', $user_id)->order(new Zend_Db_Expr('shippingaddress_id DESC'));

        return $this->fetchAll($select);
    }
}