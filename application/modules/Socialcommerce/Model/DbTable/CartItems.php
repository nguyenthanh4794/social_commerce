<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 1:08 PM
 */
class Socialcommerce_Model_DbTable_CartItems extends Engine_Db_Table
{
    protected $_name = 'socialcommerce_cartitems';

    protected $_rowClass =  'Socialcommerce_Model_CartItem';
}