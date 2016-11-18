<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 1:09 PM
 */
class Socialcommerce_Model_CartItem extends Core_Model_Item_Abstract
{
    static private $_modelProducts;

    private function getModelProducts(){
        if(self::$_modelProducts == NULL){
            self::$_modelProducts =  new Socialcommerce_Model_DbTable_Products();
        }
        return self::$_modelProducts;
    }

    public function getObject(){
        return $this->getModelProducts()->find($this->item_id)->current();
    }

    public function getItemQuantity(){
        return $this->item_qty;
    }

    public function getItemId(){
        return $this->item_id;
    }
}