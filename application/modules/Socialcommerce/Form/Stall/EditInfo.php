<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/23/2016
 * Time: 12:01 AM
 */
class Socialcommerce_Form_Stall_EditInfo extends Engine_Form
{
    public function init()
    {
        $this->addElement('Textarea', 'short_description', array(
            'label' => '*Short Description',
            'description' => 'Maximum 500 characters',
            'allowEmpty' => false,
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
            ),
        ));
        $this->short_description->getDecorator('Description')->setOption('placement', 'append');

        $upload_url = "";
        $user = Engine_Api::_()->user()->getViewer();
        if(Engine_Api::_()->authorization()->isAllowed('album', $user, 'create')){
            $upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'upload-photo'), 'socialcommerce_general', true);
        }

        $editorOptions = array(
            'upload_url' => $upload_url,
        );

        if (!empty($upload_url))
        {
            $editorOptions['plugins'] = array(
                'table', 'fullscreen', 'media', 'preview', 'paste',
                'code', 'image', 'textcolor', 'jbimages', 'link'
            );

            $editorOptions['toolbar1'] = array(
                'undo', 'redo', 'removeformat', 'pastetext', '|', 'code',
                'media', 'image', 'jbimages', 'link', 'fullscreen',
                'preview'
            );
        }

        $allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr, object , param, iframe';
        $this->addElement('TinyMce', 'description', array(
            'label' => '*Description',
            'description' => 'Maximum 500 characters',
            'required' => true,
            'allowEmpty' => false,
            'editorOptions' => $editorOptions,
            'filters' => array(
                new Engine_Filter_Censor(),
                new Engine_Filter_Html(array('AllowedTags'=>$allowed_html)),
            ),
        ));
        $this->description->getDecorator('Description')->setOption('placement', 'append');

        $this->addElement('Select', 'price_range', array(
            'label' => '*Price Range',
            'multiOptions' => array(
                '25' => 'Less than $25,000',
                '25_35' => '$25,001 to $35,000',
                '35_50' => '$35,001 to $50,000',
                '50_75' => '$50,001 to $75,000',
                '75_100' => '$75,001 to $100,000',
                '100_150' => '$100,001 to $150,000',
                '150' => '$150,001+',
            ),
        ));

        $this->addElement('Text', 'location', array(
            'label' => 'Location',
            'decorators' => array(array(
                'ViewScript',
                array(
                    'viewScript' => '_location_search.tpl',
                    'viewModule' => 'socialcommerce',
                    'class' => 'form element',
                )
            )),
        ));

        $this->addElement('hidden', 'latitude', array(
            'value' => '0',
            'order' => '98'
        ));

        $this->addElement('hidden', 'longitude', array(
            'value' => '0',
            'order' => '99'
        ));

        $this->addElement('Text', 'email', array(
            'label' => 'Email Address',
            'required' => true,
            'notEmpty' => true,
            'validators' => array(
                'EmailAddress'
            )
        ));
        $this->email->getValidator('EmailAddress')->getHostnameValidator()->setValidateTld(false);

        //Web address
        $this->addElement('Text', 'web_address', array(
            'label' => 'Web Address',
            'validators' => array(
                array('StringLength', false, array(1, 64)),
                array('Regex', true, array('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i')),
            ),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
            ),
            'class' => 'btn_form_inline',
        ));
        $this -> web_address -> getDecorator("Description") -> setOption("placement", "append") -> setEscape(FALSE);

        // init submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Save changes',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => '',
            'onclick' => 'parent.Smoothbox.close();',
            'decorators' => array(
                'ViewHelper'
            )
        ));

        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
            'style' => 'padding-bottom: 10px;'
        ));
    }

}