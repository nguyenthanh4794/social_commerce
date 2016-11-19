<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/16/2016
 * Time: 7:30 AM
 */
class Socialcommerce_Form_Admin_Transactions_Search extends Engine_Form
{
    public function init()
    {
        $this
            ->clearDecorators()
            ->addDecorator('FormElements')
            ->addDecorator('Form')
            ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
            ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'))
            ->setAttribs(array(
                'id' => 'filter_form',
                'method' => 'GET',
                'class' => 'global_form_box',
            ));

        // search by title
        //$title = new Zend_Form_Element_Text('title');

        $this->addElement('text', 'transaction_id', array(
            'label' => 'Transaction ID',
            'decorators' => array(
                array('ViewHelper'),
                array('Label', array('tag' => null, 'placement' => 'PREPEND')),
                array('HtmlTag', array('tag' => 'div'))
            )
        ));

        $this->addElement('text', 'order_id', array(
            'label' => 'Order ID',
            'decorators' => array(
                array('ViewHelper'),
                array('Label', array('tag' => null, 'placement' => 'PREPEND')),
                array('HtmlTag', array('tag' => 'div'))
            )
        ));

        $this->addElement('text', 'owner_name', array(
            'label' => 'Buyer',
            'decorators' => array(
                array('ViewHelper'),
                array('Label', array('tag' => null, 'placement' => 'PREPEND')),
                array('HtmlTag', array('tag' => 'div'))
            )
        ));

        $submit = new Zend_Form_Element_Button('search', array('type' => 'submit', 'name' => 'checksub'));
        $submit
            ->setLabel('Search')
            ->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'buttons'))
            ->addDecorator('HtmlTag2', array('tag' => 'div'));

        // Element: order
        $this->addElement('Hidden', 'order', array(
            'order' => 10004,
        ));

        // Element: direction
        $this->addElement('Hidden', 'direction', array(
            'order' => 10005,
        ));

        $this->addElements(array(
            $submit
        ));

    }
}