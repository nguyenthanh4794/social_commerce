<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/6/2016
 * Time: 11:03 PM
 */
class Socialcommerce_Form_Photo_Upload extends Engine_Form
{
    public function init()
    {
        // Init form
        $this
            ->setTitle('Add New Photos')
            ->setDescription('Choose photos on your computer to add to this deal listing. Recommended dimensions is 500 x 390 (2MB maximum)')
            ->setAttrib('id', 'form-upload')
            ->setAttrib('class', 'global_form socialcommerce_form_upload')
            ->setAttrib('name', 'albums_create')
            ->setAttrib('enctype','multipart/form-data')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
        ;

        $this -> addElement('Dummy', 'html5_upload', array('decorators' => array( array(
            'ViewScript',
            array(
                'viewScript' => '_Html5Upload.tpl',
                'class' => 'form element',
            )
        )), ));
        $this -> addElement('Hidden', 'listing_id', array('order' => 1));
        $this -> addElement('Hidden', 'html5uploadfileids', array(
            'value' => '',
            'order' => 2
        ));

        // Init submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Photos',
            'type' => 'submit',
            'decorators' => array(
                'ViewHelper',
            ),
        ));
        $this->addElement('Cancel', 'cancel', array(
            'label' => 'Cancel',
            'link' => true,
            'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('controller' => 'seller', 'action' => 'dashboard'), 'socialcommerce_general', true),
            'onclick' => '',
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