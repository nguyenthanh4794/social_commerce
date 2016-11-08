<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/9/2016
 * Time: 1:09 AM
 */
class Socialcommerce_Model_DbTable_PayTrans extends Engine_Db_Table
{
    protected $_name = 'socialcommerce_paytrans';

    protected $_rowClass = 'Socialcommerce_Model_PayTran';

    public function getByTransId($transaction_id,$gateway){
        $self = new self();
        $select =  $self->select()
            ->where('transaction_id = ?', $transaction_id)
            ->where('gateway = ?', $gateway);
        return $self->fetchRow($select);
    }
}