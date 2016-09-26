<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/26/2016
 * Time: 10:03 PM
 */
class Socialcommerce_Form_Admin_Settings_Global extends Engine_Form
{
    protected $_params = array();

    public function getParams() {
        return $this -> _params;
    }

    public function setParams($params) {
        $this -> _params = $params;
    }

    public function init() {
        $this
            ->setTitle('Global Settings')
            ->setDescription('SOCIALCOMMERCE_SETTINGS_GLOBAL_DESCRIPTION');

        $translate = Zend_Registry::get('Zend_Translate');
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $params = $this->getParams();

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

        // Element: currency
        $this->addElement('Select', 'socialcommerce_currency', array(
            'label' => 'Currency',
            'value' => $settings->getSetting('socialcommerce_currency', 'USD'),
        ));
        $this->getElement('socialcommerce_currency')->getDecorator('Description')->setOption('placement', 'APPEND');

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