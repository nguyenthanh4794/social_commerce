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

    public function getOrderItemsPaginator($params = array())
    {
        $paginator = Zend_Paginator::factory($this->getOrderItemsSelect($params));

        if( !empty($params['page']) )
        {
            $paginator->setCurrentPageNumber($params['page']);
        }
        if( !empty($params['limit']) )
        {
            $paginator->setItemCountPerPage($params['limit']);
        }
        return $paginator;
    }

    public function getOrderItemsSelect($params = array())
    {
        $table = $this;
        $rName = $table->info('name');

        $orderTable = Engine_Api::_()->getDbTable('orders', 'socialcommerce');
        $orderName = $orderTable->info('name');

        $select = $table->select()->setIntegrityCheck(false)->from($rName);

        $select->joinLeft($orderName, "$rName.order_id = $orderName.order_id", 'creation_date');

//        $select->where("$orderName.payment_status <> 'initial'");
        $select->where("$rName.object_type = 'shopping-cart'");

        // by search

        if( isset($params['order_id']) && $params['order_id'] != '')
        {
            $select->where($rName.".order_id LIKE ? ",'%'.$params['order_id'].'%');
        }

        if (!empty($params['owner_id'])) {
            $select->where("$orderName.owner_id = ?", Engine_Api::_()->user()->getViewer()->getIdentity());
        }

        if( isset($params['keyword']) && $params['keyword'] != '')
        {
            $select->where($rName.".name LIKE ? ",'\'%'.$params['keyword'].'%\'');
        }

        // Endtime
        if (isset($params['start_date']) && !empty($params['start_date'])) {
            $select -> where($orderName.".creation_date >= ?", date("Y-m-d 00-00-01", strtotime($params['start_date'])));
        }
        if (isset($params['to_date']) && !empty($params['to_date'])) {
            $select -> where($orderName.".creation_date < ?", date("Y-m-d 23-59-59", strtotime($params['to_date'])));
        }

        if( isset($params['stall_id']) && $params['stall_id'] != '')
        {
            $select->where($rName.".stall_id = ? ", $params['stall_id']);
        }


        if(isset($params['status']) && $params['status'] != ''){
            $select->where("$rName.delivery_status = ?", $params['status']);
        }

        if(isset($params['orderby']) && $params['orderby']) {
            $select->order($params['orderby'].' DESC');
        }
        elseif (!empty($params['order'])) {
            $select->order($params['order'].' '.$params['direction']);
        }

        $select->order("$rName.orderitem_id DESC");

        if(getenv('DEVMODE') == 'localdev'){
            print_r($params);
            echo $select;
        }

        return $select;
    }
}