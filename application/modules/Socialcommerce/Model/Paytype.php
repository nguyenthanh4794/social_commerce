<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 6:24 PM
 */
class Socialcommerce_Model_Paytype extends Core_Model_Item_Abstract
{
    protected $_plugin;

    public function getIdentity(){
        return $this->paytype_id;
    }
    /**
     * @return Socialstore_Plugin_Payment_Abstract
     */
    public function getPlugin() {
        if($this -> _plugin == null) {
            $plugin_class = $this -> plugin_class;
            $this -> _plugin = new $plugin_class;
        }
        return $this -> _plugin;
    }
}