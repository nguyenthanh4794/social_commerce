<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/27/2016
 * Time: 9:28 PM
 */
class Socialcommerce_Form_Payment_ShippingInformation extends Engine_Form
{
    public function init()
    {
        $this
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
        ;

        $this->addElement('Radio', 'shippinginformation_id', array(
            'label' => 'Shipping',
        ));

        $this->addElement('Textarea', 'shipping_note', array(
            'label' => 'LEAVE AN EXTRA NOTE FOR SELLER (not required)',
            'description' => 'Remaining characters' . ': 250/250',
            'placeholder' => 'Write your message',
            'maxlength'=>'250',
        ));

        $this->shipping_note->getDecorator('description')->setOption('placement', 'append');

        $this->addElement('Button', 'submit', array(
            'label' => 'Next',
            'type' => 'submit',
            'decorators' => array(
                'ViewHelper',
            ),
        ));
    }
}