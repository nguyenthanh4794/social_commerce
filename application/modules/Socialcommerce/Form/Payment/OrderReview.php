<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/8/2016
 * Time: 11:20 PM
 */
class Socialcommerce_Form_Payment_OrderReview extends Engine_Form
{
    public function init()
    {
        //Set Form Informations
        $this -> setAttribs(array('class' => 'global_form','method' => 'post'));

        $this->addElement('Hidden','token');
        $this->addElement('Hidden','PayerId');

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
            'href' => 'history.go(-1)',
            'onclick' => '',
            'decorators' => array(
                'ViewHelper'
            )
        ));

        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');

    }
}