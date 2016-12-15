<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/13/2016
 * Time: 10:51 PM
 */
class Socialcommerce_Form_Product_Create extends Engine_Form
{
    public function init()
    {
        $this->setTitle('Post a New Product')
            ->setDescription('Description.')
            ->setAttribs(array(
                    'name' => 'products_create',
                    'id' => 'products_create',
                    'class' => 'global_form',
                    'enctype' => 'multipart/form-data',
                    'action' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array()),
                )
            );
        $user = Engine_Api::_()->user()->getViewer();

        $this->addElement('Select', 'category', array(
            'label' => '*Category',
            'multiOptions' => array(
                '0' => 'Choose a category'
            )
        ));

        $this->addElement('Text', 'title', array(
            'label' => '*Product Name',
            'required' => true,
            'alowEmpty' => false,
            'validators' => array(
                array('NotEmpty', true),
                array('StringLength', true, array('max' => 128))
            )
        ));

        $allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr, object , param, iframe';
        $this->addElement('TinyMce', 'description', array(
            'label' => '*Description',
            'description' => 'Maximum 500 characters',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array(
                new Engine_Filter_Censor(),
                new Engine_Filter_Html(array('AllowedTags'=>$allowed_html)),
            ),
        ));
        $this->description->getDecorator('Description')->setOption('placement', 'append');

        $this->addElement('File', 'main_photo', array(
            'label' => '*Main Photo',
            'destination' => APPLICATION_PATH.'/public/temporary/',
            'multiFile' => 1,
            'validators' => array(
                array('Count', false, 1),
                array('Extension', false, 'jpg,jpeg,png,gif'),
            ),
        ));


        $this->addElement('Text', 'price', array(
            'label' => '*Price',
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(
                array('Float', true),
                new Engine_Validate_AtLeast(0),
            ),
            'value' => '0.00',
        ));

        // init submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Post',
            'type' => 'submit',
            'ignore' => true,
            'class' => 'yn-btn-success',
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('Button', 'draft', array(
            'label' => 'Save as Draft',
            'type' => 'submit',
            'ignore' => true,
            'class' => 'yn-btn-default',
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => '',
            'onClick'=> 'history.go(-1);',
            'decorators' => array(
                'ViewHelper'
            )
        ));
        $this->addDisplayGroup(array('submit', 'draft', 'cancel'), 'buttons', array(
            'style' => 'padding-bottom: 10px;'
        ));
    }
}