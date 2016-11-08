<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 1:10 PM
 */
class Socialcommerce_Model_DbTable_Carts extends Engine_Db_Table
{
    protected $_name = 'socialcommerce_carts';

    protected $_rowClass =  'Socialcommerce_Model_Cart';
}