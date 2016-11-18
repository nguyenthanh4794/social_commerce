<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/8/2016
 * Time: 11:28 PM
 */
class Socialcommerce_Form_Payment_Gateway extends Engine_Form
{
    public function init()
    {
        //Set Form Informations
        $this -> setAttribs(array('class' => 'global_form','method' => 'post'))
            -> setTitle('Select a gateway to purchase')
            -> setDescription('Please choose the gateway which is used to purchase');

            $gateways = Engine_Api::_()->getDbTable('gateways', 'payment')->getEnabledGateways();

        if(!count($gateways)){
            $this->addError('Sorry, there are no available gateways at this time.');
            return ;
        }

        $groups = array();
        $this->addElement('hidden','gateway');

        foreach($gateways as $gateway){
            $name = strtolower($gateway->getTitle());
            // Buttons
            $this->addElement('Button', $name, array(
                'label' => sprintf("Pay with %s", $gateway->getTitle()),
                'type' => 'button',
                'onclick' => "this.form.gateway.value='$name'; this.form.submit()",
                'ignore' => true,
                'decorators' => array('ViewHelper')
            ));
            $groups[] = $name;
        }

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
        $groups[] =  'cancel';

        $this->addDisplayGroup($groups, 'buttons');

    }
}