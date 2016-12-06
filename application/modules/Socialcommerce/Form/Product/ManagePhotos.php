<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/6/2016
 * Time: 9:59 PM
 */
class Socialcommerce_Form_Product_ManagePhotos extends Engine_Form
{
    public function init()
    {
        $this
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
        ;

        $this->addElement('Radio', 'cover', array(
            'label' => 'Main Photo',
        ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'decorators' => array(
                'ViewHelper',
            ),
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'decorators' => array(
                'ViewHelper',
            ),
        ));

        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
            'decorators' => array(
                'FormElements',
                'DivDivDivWrapper',
            ),
        ));
    }
}