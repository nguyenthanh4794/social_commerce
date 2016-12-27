<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 12:40 PM
 */
class Socialcommerce_Model_Request extends Core_Model_Item_Abstract
{
    public function getStall()
    {
        return Engine_Api::_()->getItem('socialcommerce_stall', $this->stall_id);
    }

    public function isWaitingToProcess(){
        return $this->request_status == 'waiting';
    }

    public function getAccount(){
        $Table =  Engine_Api::_()->getDbTable('paypalaccounts', 'socialcommerce');
        $select =  $Table->select()->where('owner_id = ?', $this->owner_id);
        $item =  $Table->fetchRow($select);
        return $item;
    }
}