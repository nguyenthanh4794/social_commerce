<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/27/2016
 * Time: 11:03 PM
 */
class Socialcommerce_Form_Admin_Packages_Create extends Engine_Form
{
    public function init()
    {
        $this
            ->setTitle('Create Payment Plan')
            ->setDescription('Missing description.')
        ;

        // Element: Heading
        $this->addElement('Heading', 'general_infomation', array(
            'label' => 'General Information'
        ));

        // Element: title
        $this->addElement('Text', 'title', array(
            'label' => 'Title',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array(
                'StringTrim',
            ),
        ));

        // Element: Heading
        $this->addElement('Heading', 'money_distribution', array(
            'label' => 'Money Distribution'
        ));

        // Element: seller_receive
        $this->addElement('Integer', 'seller_receive', array(
            'label' => 'Seller Receive',
            'description' => 'The part seller receive per each sold items after all commission
                                Excluding Publishing Fee or other services fee',
            'validators' => array(
                new Engine_Validate_AtLeast(0),
            ),
        ));

        // Element: admin_receive
        $this->addElement('Integer', 'admin_receive', array(
            'label' => 'Admin Receive',
            'description' => 'Considered as commission fee',
            'validators' => array(
                new Engine_Validate_AtLeast(0),
            ),
        ));

        // Element: money_distribution_method
        $this->addElement('Radio', 'money_distribution_method', array(
            'label' => 'Money Distribution Method',
            'multiOptions' => array(
                2 => 'Admin auto approve payment for seller periodically',
                1 => 'Seller request for money',
                0 => 'Money distributed to target receipients',
            )
        ));

        // Element: Heading
        $this->addElement('Heading', 'product_publishing', array(
            'label' => 'Product Publishing'
        ));

        $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
        // Element:
        $this->addElement('Text', 'number_first_publish', array(
           'label' => 'Number Of "First Publication"',
            'description' => $currency.'/product',
            'validators' => array(
                array('Float', true),
                new Engine_Validate_AtLeast(0),
            ),
            'value' => '0.00',
        ));
        $this->number_first_publish->getDecorator('Description')->setOption('placement', 'APPEND');

        // Element:
        $this->addElement('Text', 'fee_first_publish', array(
           'label' => 'Fee For Each "First Publication"',
            'description' => $currency.'/product',
            'validators' => array(
                array('Float', true),
                new Engine_Validate_AtLeast(0),
            ),
            'value' => '0.00',
        ));
        $this->fee_first_publish->getDecorator('Description')->setOption('placement', 'APPEND');

        // Element:
        $this->addElement('Text', 'fee_normal', array(
           'label' => 'Fee For Normal Publication',
            'description' => $currency.'/product',
            'validators' => array(
                array('Float', true),
                new Engine_Validate_AtLeast(0),
            ),
            'value' => '0.00',
        ));
        $this->fee_normal->getDecorator('Description')->setOption('placement', 'APPEND');

        $this->addElement('Multicheckbox', 'payment_gateway', array(
           'label' => 'System Payment Gateway'
        ));

        // Element: execute
        $this->addElement('Button', 'execute', array(
            'label' => 'Create Plan',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper'),
        ));

        // Element: cancel
        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'prependText' => ' or ',
            'ignore' => true,
            'link' => true,
            'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index', 'package_id' => null)),
            'decorators' => array('ViewHelper'),
        ));

        // DisplayGroup: buttons
        $this->addDisplayGroup(array('execute', 'cancel'), 'buttons', array(
            'decorators' => array(
                'FormElements',
                'DivDivDivWrapper',
            )
        ));
    }
}