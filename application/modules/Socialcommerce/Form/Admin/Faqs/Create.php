<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 3:58 PM
 */
class Socialcommerce_Form_Admin_Faqs_Create extends Engine_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setTitle('Add New Faq')->setDescription('Create a FAQ to display in Faqs pages.');

        $this->addElement('Hidden','id');

        // Form Name - Required
        $this->addElement('Text', 'question',array(
            'label'     => 'Question',
            'required'  => true,
            'allowEmpty'=> false,
            'autocomplete' => 'off',
            'filters' => array(
                new Engine_Filter_Censor(),
                'StripTags'
            ),
        ));

        $editorOptions['toolbar1'] = array(
            'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
        );

        $editorOptions['toolbar2'] = array(
            'print preview media | forecolor backcolor emoticons | codesample'
        );

        $allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr, object , param, iframe';
        // Form description
        $this->addElement('TinyMce', 'answer', array(
            'label' => 'Answer',
            'required' => true,
            'allowEmpty' => false,
            'editorOptions' => $editorOptions,
            'filters' => array(
                new Engine_Filter_Censor(),
                new Engine_Filter_Html(array('AllowedTags'=>$allowed_html)),
            ),
        ));

        // Enable Form
        $this->addElement('Checkbox', 'status', array(
            'label' => 'Show this faq',
            'value' => '1',
            'description' => 'Status',
        ));

        // Buttons
        $this->addElement('Button', 'submit', array(
            'label' => 'Create',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => '',
            'onClick'=> 'javascript:parent.Smoothbox.close();',
            'decorators' => array(
                'ViewHelper'
            )
        ));
        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
            'decorators' => array(
                'FormElements',
                'DivDivDivWrapper',
            ),
        ));
    }
}