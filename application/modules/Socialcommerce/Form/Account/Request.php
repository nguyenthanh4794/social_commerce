<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/20/2016
 * Time: 11:32 AM
 */
class Socialcommerce_Form_Account_Request extends Engine_Form
{
    public function init()
    {
        $this->getView();
        $viewer = Engine_Api::_()->user()->getViewer();
        $currency = Engine_Api::_() -> getApi('settings', 'core')->getSetting('payment.currency', 'USD');

        // $this->setTitle('Create request');
        $min_payout = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.minWithdrawSeller', 5.00);
        $max_payout = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.maxWithdrawSeller', 100.00);


        $user_id = $viewer->getIdentity();
        $requested_amount = Socialcommerce_Api_Account::getTotalRequest($user_id, 1);
        $info_account = Socialcommerce_Api_Account::getCurrentAccount($user_id);
        $rest = $info_account['total_amount'] - $requested_amount;
        if ($rest <= $max_payout) {
            $maxvalue = $rest;
        } else {
            $maxvalue = $max_payout;
        }
        if ($rest < $min_payout) {
            $this->addNotice("You can not request, because available amount is smaller than minimum amount to request.");
        } else {
            $this->addElement('Text', 'txtrequest_money', array(
                'label' => 'Amount (' . $currency . ')',
                'placeholder' => Zend_Registry::get('Zend_Translate')->_('Start from') . ' ' . $this->_view->locale()->toCurrency($min_payout, $currency),
                'allowEmpty' => false,
                'required' => true,
                'validators' => array(
                    array('NotEmpty', true),
                    array('Float', true),
                    array('Between', true, array($min_payout, $maxvalue, true)),
                ),
                'filters' => array(
                    new Engine_Filter_Censor(),
                ),
                'value' => '',
            ));
            $this->addElement('Textarea', 'textarea_request', array(
                'label' => 'Message',
                'description' => '',
                'required' => false,
                'allowEmpty' => true,
                'maxlength' => 300,
                'validators' => array(
                    array('NotEmpty', true),
                ),
                'description' => 'Maximum 300 characters'
            ));
            $this->textarea_request->getDecorator("Description")->setOption("placement", "append");
            $message_validator = new Zend_Validate_StringLength(array('min' => 1, 'max' => 300));
            $message_validator->setMessages(array(Zend_Validate_StringLength::TOO_LONG => 'Title is more than 300 characters long'));
            $this->textarea_request->addValidator($message_validator);

            $this->addElement('Button', 'submit', array(
                'label' => 'Request',
                'type' => 'submit',
                'ignore' => true,
                'decorators' => array(
                    'ViewHelper',
                ),
            ));
            $this->addElement('Hidden', 'deal', array(
                'order' => 100
            ));

            $this->addElement('Hidden', 'number_buy', array(
                'order' => 102
            ));

            $this->addElement('Hidden', 'total_amount', array(
                'order' => 103
            ));
            // Element: cancel
            $this->addElement('Cancel', 'cancel', array(
                'label' => 'cancel',
                'link' => true,
                'onClick' => 'javascript:parent.Smoothbox.close();',
                'decorators' => array(
                    'ViewHelper',
                ),
            ));
            // DisplayGroup: buttons
            $this->addDisplayGroup(array(
                'submit',
                'cancel',
            ), 'buttons', array(
                'decorators' => array(
                    'FormElements',
                    'DivDivDivWrapper'
                ),
            ));
        }
    }
}