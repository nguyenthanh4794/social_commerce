<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/13/2016
 * Time: 9:54 PM
 */
class Socialcommerce_Model_DbTable_Packages extends Engine_Db_Table
{
    protected $_rowClass = 'Socialcommerce_Model_Package';
    protected $_serializedColumns = array('themes', 'category_id');
}