<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 8:01 PM
 */
class Socialcommerce_Model_DbTable_OrderItems extends Engine_Db_Table
{
    protected $_name = 'socialcommerce_orderitems';

    protected $_rowClass = 'Socialcommerce_Model_OrderItem';

    static public function getByOrderItemId($orderitem_id){
        $self =  new self;
        return $self->find($orderitem_id)->current();
    }

    public function deleteOrderItemById($id) {
        $self = new self;
        $item = $self->find($id)->current();
        $item->delete();
    }

    public function getHandling($order_id) {
        $select = $this->select()->from($this->info('name'),array('order_handling_amount','shippingrule_id'))->where('order_id = ?', $order_id);
        $results = $this->fetchAll($select);
    }

    public function getOldIds($order_id) {
        $select = $this->select()->where('order_id = ?', $order_id);
        $results = $this->fetchAll($select);
        $return = array();
        foreach ($results as $result) {
            $return[] = $result->orderitem_id;
        }
        return $return;
    }
}