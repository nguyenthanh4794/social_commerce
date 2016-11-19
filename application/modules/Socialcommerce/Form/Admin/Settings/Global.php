<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/26/2016
 * Time: 10:03 PM
 */
class Socialcommerce_Form_Admin_Settings_Global extends Engine_Form
{
    public function init() {
        $this
            ->setTitle('Global Settings')
            ->setDescription('SOCIALCOMMERCE_SETTINGS_GLOBAL_DESCRIPTION');

        $settings = Engine_Api::_()->getApi('settings', 'core');

        $this -> addElement('Text', 'socialcommerce_addthis_pubid', array(
            'label' => 'Addthis - Profile ID',
            'description' => 'Please refer to this guide to get Addthis - Profile ID: 
                            <a href="https://www.addthis.com/get/share" />https://www.addthis.com/get/share</a>',
            'value' => Engine_Api::_() -> getApi('settings', 'core') -> getSetting('socialcommerce.addthis.pubid', 'younet'),
        ));

        $this->socialcommerce_addthis_pubid->getDecorator('Description')->setEscape(false);

        $this -> addElement('Text', 'socialcommerce_google_api_key', array(
            'label' => 'Google API Key',
            'description' => 'Please refer to this guide to get Google API Key: 
                            <a href="https://developers.google.com/places/web-service/get-api-key" />https://developers.google.com/places/web-service/get-api-key</a>',
            'value' => Engine_Api::_() -> getApi('settings', 'core') -> getSetting('socialcommerce.google.api.key', 'AIzaSyB3LowZcG12R1nclRd9NrwRgIxZNxLMjgc'),
        ));
        $this->socialcommerce_google_api_key->getDecorator('Description')->setEscape(false);

        // Element: Payment Plans
        $this->addElement('Select', 'socialcommerce_payment_plan', array(
            'label' => 'Payment Plan For Seller To Use',
            'multiOptions' => array(
                'completely' => 'Receive Completely',
                'after_commission' => 'Receive After Commission',
                'after_request' => 'Receive After Request',
            ),
            'value' => $settings->getSetting('socialcommerce_payment_plan', 'completely'),
        ));

        $this->addElement('Button', 'submit_btn', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));
    }
}