<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/15/2016
 * Time: 10:37 PM
 */
class Socialcommerce_Model_DbTable_Reviews extends Engine_Db_Table
{
    protected $_rowClass = 'Socialcommerce_Model_Review';

    public function getReviewsPaginator($params = array()) {
        return Zend_Paginator::factory($this->getReviewsSelect($params));
    }

    public function getReviewsSelect($params = array()){
        $table = Engine_Api::_()->getItemTable('socialcommerce_review');
        $tableName = $table->info('name');

        $select = $table
            ->select()
            ->from($tableName);

        // User
        if( !empty($params['user_id']) ) {
            $select
                ->where("$tableName.user_id = ?", $params['user_id']);
        }

        if( !empty($params['type']) ) {
            $select
                ->where("$tableName.type = ?", $params['type']);
        }

        //Listing
        if(isset ($params['item_id'])){
            $select
                ->where("$tableName.item_id = ?", $params['item_id']);
        }

        $select->order('creation_date DESC');

        return $select;
    }
}