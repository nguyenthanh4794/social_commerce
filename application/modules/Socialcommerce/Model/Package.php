<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/13/2016
 * Time: 9:55 PM
 */
class Socialcommerce_Model_Package extends Core_Model_Item_Abstract
{
    public function getTitle() {
        return $this->title;
    }

    public function getPrice(){
        $view = Zend_Registry::get('Zend_View');
        $currency = Engine_Api::_() -> getApi('settings', 'core') -> getSetting('payment.currency', 'USD');
        return ($view -> locale()->toCurrency($this->price, $currency));
    }
}