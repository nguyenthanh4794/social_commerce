<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/15/2016
 * Time: 12:26 AM
 */
class Socialcommerce_Form_Review_Create extends Engine_Form
{
    public function init()
    {
        $this->setTitle('Review Product')
            ->setDescription('Description.')
            ->setAttribs(array(
                    'class' => 'global_form_sc',
                )
            );
        $this -> addElement('dummy', 'rate_number', array(
            'decorators' => array( array(
                'ViewScript',
                array(
                    'viewScript' => 'fields/_StarRating.tpl',
                    'class' => 'form element',
                    'id' => 'rate_number',
                )
            )),
        ));

        $this->addElement('Text', 'title', array(
            'label' => 'Review Title',
            'placeholder' => 'Title of the review...',
            'allowEmpty' => false,
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                array('StringLength', false, array(1, 128)),
            ),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
            ),
        ));

        $this->addElement('Textarea', 'body', array(
            'label' => 'Content',
            'filters' => array(
                new Engine_Filter_HtmlSpecialChars(),
                new Engine_Filter_Censor(),
                new Engine_Filter_EnableLinks(),
            ),
        ));

        // Buttons
        $this->addElement('Button', 'submit', array(
            'value' => 'submit',
            'label' => 'Review',
            'onclick' => 'removeSubmit()',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array(
                'ViewHelper',
            ),
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'Cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => '',
            'onclick' => 'parent.Smoothbox.close();',
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