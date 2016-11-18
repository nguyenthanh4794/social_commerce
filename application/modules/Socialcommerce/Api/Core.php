<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/27/2016
 * Time: 9:28 PM
 */
class Socialcommerce_Api_Core extends Core_Api_Abstract
{
    public function typeCreate($label) {
        $field = Engine_Api::_() -> fields() -> getField('1', 'socialcommerce_listing');
        // Create new blank option
        $option = Engine_Api::_() -> fields() -> createOption('socialcommerce_listing', $field, array('field_id' => $field -> field_id, 'label' => $label, ));
        // Get data
        $mapData = Engine_Api::_() -> fields() -> getFieldsMaps('socialcommerce_listing');
        $metaData = Engine_Api::_() -> fields() -> getFieldsMeta('socialcommerce_listing');
        $optionData = Engine_Api::_() -> fields() -> getFieldsOptions('socialcommerce_listing');
        // Flush cache
        $mapData -> getTable() -> flushCache();
        $metaData -> getTable() -> flushCache();
        $optionData -> getTable() -> flushCache();

        return $option -> option_id;
    }

    public function getItemTable($type)
    {
        if ($type == 'socialcommerce_stall') {
            return Engine_Loader::getInstance()->load('Socialcommerce_Model_DbTable_Stalls');
        } else
            if ($type == 'socialcommerce_category') {
                return Engine_Loader::getInstance()->load('Socialcommerce_Model_DbTable_Categories');
            } else {
                $class = Engine_Api::_()->getItemTableClass($type);
                return Engine_Api::_()->loadClass($class);
            }
    }

    public function getStallById($stall_id)
    {
        return Engine_Api::_()->getItem('socialcommerce_stall', $stall_id);
    }

    public function isSandboxMode()
    {
        return true;
    }
}