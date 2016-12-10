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

        $availableLabels = array(
            'everyone'            => 'Everyone',
            'registered'          => 'All Registered Members',
            'owner_network'       => 'Friends and Networks',
            'owner_member_member' => 'Friends of Friends',
            'owner_member'        => 'Friends Only',
            'owner'               => 'Just Me'
        );

        // Element: auth_view
        $viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('socialcommerce_product', $user, 'auth_view');
        $viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));

        if( !empty($viewOptions) && count($viewOptions) >= 1 ) {
            // Make a hidden field
            if(count($viewOptions) == 1) {
                $this->addElement('hidden', 'auth_view', array('value' => key($viewOptions)));
                // Make select box
            } else {
                $this->addElement('Select', 'auth_view', array(
                    'label' => 'Viewing Privacy',
                    'description' => 'Who may see this listing?',
                    'multiOptions' => $viewOptions,
                    'value' => key($viewOptions),
                ));
                $this->auth_view->getDecorator('Description')->setOption('placement', 'append');
            }
        }

        // Element: auth_comment
        $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('socialcommerce_product', $user, 'auth_comment');
        $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));

        if( !empty($commentOptions) && count($commentOptions) >= 1 ) {
            // Make a hidden field
            if(count($commentOptions) == 1) {
                $this->addElement('hidden', 'auth_comment', array('value' => key($commentOptions)));
                // Make select box
            } else {
                $this->addElement('Select', 'auth_comment', array(
                    'label' => 'Commenting Privacy',
                    'description' => 'Who may post comments on this listing?',
                    'multiOptions' => $commentOptions,
                    'value' => key($commentOptions),
                ));
                $this->auth_comment->getDecorator('Description')->setOption('placement', 'append');
            }
        }

        // Element: auth_comment
        $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('socialcommerce_product', $user, 'auth_share');
        $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));

        if( !empty($commentOptions) && count($commentOptions) >= 1 ) {
            // Make a hidden field
            if(count($commentOptions) == 1) {
                $this->addElement('hidden', 'auth_share', array('value' => key($commentOptions)));
                // Make select box
            } else {
                $this->addElement('Select', 'auth_share', array(
                    'label' => 'Sharing Privacy',
                    'description' => 'Who may share this listing?',
                    'multiOptions' => $commentOptions,
                    'value' => key($commentOptions),
                ));
                $this->auth_share->getDecorator('Description')->setOption('placement', 'append');
            }
        }

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