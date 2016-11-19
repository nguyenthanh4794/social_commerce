<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/9/2016
 * Time: 12:58 AM
 */
class Socialcommerce_Form_Payment_Review extends Engine_Form
{
    public function init()
    {
        //Set Form Informations
        $this->setAttribs(array('class' => 'global_form', 'method' => 'post'))
            ->setTitle('Complete the payment')
            ->setDescription('You have just chosen paypal as your payment gateway. Please confirm if you really want to purchase.');

        //VAT Id

        $this->addElement('Hidden', 'token');
        $this->addElement('Hidden', 'PayerId');

        // Buttons
        $this->addElement('Button', 'submit', array(
            'label' => 'Confirm',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'socialcommerce_general', true),
            'onclick' => '',
            'decorators' => array(
                'ViewHelper'
            )
        ));

        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');

    }
}