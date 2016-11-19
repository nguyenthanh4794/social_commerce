<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 3:06 PM
 */
class Socialcommerce_Model_DbTable_Orders extends Engine_Db_Table
{
    /**
     * Model class name
     * @var string
     */
    protected $_rowClass = 'Socialcommerce_Model_Order';

    /**
     * Model table name
     * @var string
     */
    protected $_name =  'socialcommerce_orders';

    public function fetchNew(){
        $item =  parent::fetchNew();
        $length = 12;
        $item->order_id = self::generateCode($length);
        $item->creation_date =  date('Y-m-d H:i:s');
        return $item;
    }

    /**
     * @return $string
     */
    static public function generateCode($length){
        $seeks =  '1234567890ZXCVBNMASDFGHJKLQWERTYUIOP';
        $max =  strlen($seeks)-1;
        $result =  '';
        for($i=0; $i<$length; ++$i){
            $result .= substr($seeks, mt_rand(0, $max),1);
        }
        return $result;
    }

    static public function getByOrderId($order_id){
        $self =  new self;
        return $self->find($order_id)->current();
    }

    public function getOrdersPaginator($params = array())
    {
        $paginator = Zend_Paginator::factory($this->getOrdersSelect($params));

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

    public function getOrdersSelect($params = array())
    {
        $table = $this;
        $rName = $table->info('name');
        $select = $table->select()->from($rName)->setIntegrityCheck(false);
        $userTable  = new User_Model_DbTable_Users;
        $userName = $userTable->info('name');
        $select -> joinLeft($userName, "owner_id = user_id",'username as owner_name');
        $select->where("payment_status <> 'initial'");

        // by search

        if( isset($params['order_id']) && $params['order_id'] != '')
        {
            $select->where($rName.".order_id LIKE ? ",'%'.$params['order_id'].'%');
        }

        // by Buyer

        if(!empty($params['owner_name']) && $params['owner_name'] != "") {
            $select->where("$userName.username LIKE ?",'%'.$params['owner_name'].'%');
        }

        if(!empty($params['paytype_id']) && $params['paytype_id'] != "")
            $select->where("$rName.paytype_id = ?",$params['paytype_id']);

        // by status
        if(!empty($params['user_id']) && is_numeric($params['user_id']))
            $select->where("$rName.owner_id = ?",$params['user_id']);

        if(isset($params['status']) && $params['status'] != ''){
            $select->where("$rName.state = ?", $params['status']);
        }
        if(isset($params['orderby']) && $params['orderby']) {
            $select->order($params['orderby'].' DESC');
        }
        elseif (!empty($params['order'])) {
            $select->order($params['order'].' '.$params['direction']);
        }
        else
        {
            $select->order("$rName.creation_date DESC");
        }


        if(getenv('DEVMODE') == 'localdev'){
            print_r($params);
            echo $select;
        }

        return $select;
    }
}