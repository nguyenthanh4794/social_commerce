<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 1:11 PM
 */
class Socialcommerce_Model_Cart extends Core_Model_Item_Abstract
{
    public function getId(){
        return $this->cart_id;
    }
}