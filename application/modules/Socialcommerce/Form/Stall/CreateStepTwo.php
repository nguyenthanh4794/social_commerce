<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/15/2016
 * Time: 11:17 AM
 */
class Socialcommerce_Form_Stall_CreateStepTwo extends Engine_Form
{
    public function init()
    {
        $this
            ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('name', 'EditStallPhoto');

        $this->addElement('Image', 'current', array(
            'label' => 'Current Photo',
            'ignore' => true,
            'decorators' => array(array('ViewScript', array(
                'viewScript' => '_formEditImage.tpl',
                //'viewScript' => '_formImageCrop.tpl',
                'class'      => 'form element',
                'testing' => 'testing'
            )))
        ));
        Engine_Form::addDefaultDecorators($this->current);

        $this->addElement('File', 'Filedata', array(
            'label' => 'Choose New Photo',
            'destination' => APPLICATION_PATH.'/public/temporary/',
            'multiFile' => 1,
            'validators' => array(
                array('Count', false, 1),
                // array('Size', false, 612000),
                array('Extension', false, 'jpg,jpeg,png,gif'),
            ),
            'onchange'=>'javascript:uploadSignupPhoto();'
        ));

        $this->addElement('Image', 'current_cover', array(
            'label' => 'Current Cover Photo',
            'ignore' => true,
            'decorators' => array(array('ViewScript', array(
                'viewScript' => '_formEditCoverImage.tpl',
                //'viewScript' => '_formImageCrop.tpl',
                'class'      => 'form element',
                'testing' => 'testing'
            )))
        ));
        Engine_Form::addDefaultDecorators($this->current_cover);

        $this->addElement('File', 'FileCoverdata', array(
            'label' => 'Choose New Cover Photo',
            'destination' => APPLICATION_PATH.'/public/temporary/',
            'multiFile' => 1,
            'validators' => array(
                array('Count', false, 1),
                // array('Size', false, 612000),
                array('Extension', false, 'jpg,jpeg,png,gif'),
            ),
            'onchange'=>'javascript:uploadSignupPhoto();'
        ));

        $this->addElement('Hidden', 'coordinates', array(
            'filters' => array(
                'HtmlEntities',
            )
        ));

        $this->addElement('Button', 'done', array(
            'label' => 'Save Photo',
            'type' => 'submit',
            'decorators' => array(
                'ViewHelper'
            ),
        ));

        $this->addElement('Cancel', 'remove', array(
            'label' => 'remove photo',
            'link' => true,
            'prependText' => ' or ',
            'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
                'action' => 'remove-photo',
            )),
            'onclick' => null,
            'class' => 'smoothbox',
            'decorators' => array(
                'ViewHelper'
            ),
        ));

        $this->addDisplayGroup(array('done', 'remove'), 'buttons');
    }
}