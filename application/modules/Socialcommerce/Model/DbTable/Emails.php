<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/10/2016
 * Time: 9:41 PM
 */
class Socialcommerce_Model_DbTable_Emails extends Engine_Db_Table
{
    protected $_rowClass = 'Socialcommerce_Model_Email';

    public function add($params){

        $item  = $this->fetchNew();
        $item->setFromArray($params);
        $item->save();
        return $item;
    }
}