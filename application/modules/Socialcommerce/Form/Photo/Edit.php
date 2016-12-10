<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/6/2016
 * Time: 10:09 PM
 */
class Socialcommerce_Form_Photo_Edit extends Engine_Form
{
    public function init()
    {
        $this->clearDecorators()
            ->addDecorator('FormElements');

        $this->addElement('Checkbox', 'delete', array(
            'label' => "Delete Photo",
            'decorators' => array(
                'ViewHelper',
                array('Label', array('placement' => 'APPEND')),
                array('HtmlTag', array('tag' => 'div', 'class' => 'photo-delete-wrapper')),
            ),
        ));


        $this->addElement('Hidden', 'photo_id', array(
            'validators' => array(
                'Int',
            )
        ));
    }
}