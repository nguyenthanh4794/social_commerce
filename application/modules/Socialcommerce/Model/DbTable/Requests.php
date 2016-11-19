<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 12:39 PM
 */
class Socialcommerce_Model_DbTable_Requests extends Engine_Db_Table
{
    protected $_name = 'socialcommerce_requests';

    protected $_rowClass =  'Socialcommerce_Model_Request';
}