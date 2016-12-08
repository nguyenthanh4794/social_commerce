<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/7/2016
 * Time: 8:35 PM
 */
class Socialcommerce_Form_Admin_Settings_Fee extends Engine_Form
{
    public function init()
    {
        $this
            ->setTitle('Manage Fee Schedule')
            ->setDescription('SOCIALCOMMERCE_SETTINGS_GLOBAL_DESCRIPTION');

        $settings = Engine_Api::_()->getApi('settings', 'core');
        $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');

        $this -> addElement('Text', 'socialcommerce_create_stall', array(
            'label' => 'Create Stall Fee',
            'description' => $currency.' / stall',
            'value' => Engine_Api::_() -> getApi('settings', 'core') -> getSetting('socialcommerce.create.stall', 40),
        ));

        $this->socialcommerce_create_stall->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'style' => 'display: inline'));

        $this -> addElement('Text', 'socialcommerce_create_listing', array(
            'label' => 'Listing Item Fee',
            'description' => $currency.' / item',
            'value' => Engine_Api::_() -> getApi('settings', 'core') -> getSetting('socialcommerce.create.listing', 1),
        ));

        $this->socialcommerce_create_listing->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'style' => 'display: inline'));

        $this -> addElement('Text', 'socialcommerce_selling_item_stall', array(
            'label' => 'Selling Fee For Item In Stall',
            'description' => $currency.' / sold item',
            'value' => Engine_Api::_() -> getApi('settings', 'core') -> getSetting('socialcommerce.selling.item.stall', 0),
        ));

        $this->socialcommerce_selling_item_stall->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'style' => 'display: inline'));

        $this -> addElement('Text', 'socialcommerce_selling_item_global', array(
            'label' => 'Selling Fee For Global Item',
            'description' => $currency.' / sold item',
            'value' => Engine_Api::_() -> getApi('settings', 'core') -> getSetting('socialcommerce.selling.item.global', 1),
        ));

        $this->socialcommerce_selling_item_global->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'style' => 'display: inline'));

        $this->addElement('Button', 'submit_btn', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));
    }
}