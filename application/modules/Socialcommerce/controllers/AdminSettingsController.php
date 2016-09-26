<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/26/2016
 * Time: 9:59 PM
 */
class Socialcommerce_AdminSettingsController extends Core_Controller_Action_Admin
{
    public function globalAction()
    {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_settings_global');
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $params = array();
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getPost();
        }

        $this->view->form = $form = new Socialcommerce_Form_Admin_Settings_Global(array('params' => $params));

        // Populate currency options
        $supportedCurrencies = array();
        $gateways = array();
        $gatewaysTable = Engine_Api::_()->getDbtable('gateways', 'payment');
        foreach( $gatewaysTable->fetchAll(/*array('enabled = ?' => 1)*/) as $gateway ) {
            $gateways[$gateway->gateway_id] = $gateway->title;
            $gatewayObject = $gateway->getGateway();
            $currencies = $gatewayObject->getSupportedCurrencies();
            if( empty($currencies) ) {
                continue;
            }
            $supportedCurrencyIndex[$gateway->title] = $currencies;
            if( empty($fullySupportedCurrencies) ) {
                $fullySupportedCurrencies = $currencies;
            } else {
                $fullySupportedCurrencies = array_intersect($fullySupportedCurrencies, $currencies);
            }
            $supportedCurrencies = array_merge($supportedCurrencies, $currencies);
        }
        $supportedCurrencies = array_diff($supportedCurrencies, $fullySupportedCurrencies);

        $translationList = Zend_Locale::getTranslationList('nametocurrency', Zend_Registry::get('Locale'));
        $fullySupportedCurrencies = array_intersect_key($translationList, array_flip($fullySupportedCurrencies));
        $supportedCurrencies = array_intersect_key($translationList, array_flip($supportedCurrencies));
        $form->getElement('socialcommerce_currency')->setMultiOptions(array(
            'Fully Supported' => $fullySupportedCurrencies,
            'Partially Supported' => $supportedCurrencies,
        ));

        $this->view->gateways = $gateways;
        $this->view->supportedCurrencyIndex = $supportedCurrencyIndex;

        if (!$this->getRequest()->isPost()){
            return;
        }
        $p_valid = $this->getRequest()->getPost();
        $colorSettings = array(
            'menu_backgroundcolor',
            'menu_hovercolor',
            'menu_textcolor',
            'menu_backgroundbar'
        );
        $formValues  = $form->getValues();
        foreach ($colorSettings as $setting) {
            $p_valid['socialcommerce_'.$setting] = $formValues['socialcommerce_'.$setting];
        }
        if($form->isValid($p_valid)) {
            $p_post = $this->getRequest()->getPost();
            $values = $form->getValues();
            foreach ($colorSettings as $setting) {
                $values['socialcommerce_'.$setting] = $p_post[$setting];
            }
            unset($values['image']);
            foreach ($values as $key => $value) {
                $settings->setSetting($key, $value);
            }
            $form->addNotice('Your changes have been saved.');
        }
    }
}