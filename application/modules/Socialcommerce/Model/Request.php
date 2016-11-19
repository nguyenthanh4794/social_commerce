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

    public function getAccount($gateway ='paypal'){
        $Table =  Engine_Api::_()->getDbTable('accounts', 'socialcommerce');
        $select =  $Table->select()->where('user_id=?', $this->owner_id)->where('gateway_id=?',$gateway);
        $item =  $Table->fetchRow($select);
        return $item;
    }
}