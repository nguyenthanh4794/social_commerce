<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/26/2016
 * Time: 11:21 PM
 */
class Socialcommerce_Form_Account_CreatePayPalAccount extends Engine_Form
{
    public function init() {
        $this->setTitle('Add PayPal Account')
            ->setDescription('Add your paypal account to request money. (*) is required.');

        $this->setAttribs(array(
            'id'=>'form_edit',
            'class'=>'global_form'
        ));

        $this->addElement('Text','name',array(
            'label'=>'Paypal Display Name*',
            'description'=>'Please fill your Paypal Display Name in the textbox below.',
            'required'=>true,
            'maxlength'=>128,
            'filters'=>array('StringTrim'),
        ));

        $this->addElement('Text','account_username',array(
            'label'=>'Paypal Email Address*',
            'required'=>true,
            'maxlength'=>128,
            'description'=>'Please fill your Paypal Email Address in the textbox below exactly. This email address will be used to receive the money from admin when you request money.',
            'filters'=>array('StringTrim'),
            'validators'=>array(
                'EmailAddress',
            ),
        ));

        /**
         * add button groups
         */
        $this->addElement('Button', 'submit', array(
            'label' => 'Submit',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array(
                'ViewHelper',
            ),
        ));


        // Element: cancel
        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'onClick'=> 'javascript:parent.Smoothbox.close();',
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