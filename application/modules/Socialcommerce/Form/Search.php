<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/15/2016
 * Time: 12:37 PM
 */
class Socialcommerce_Form_Search extends Fields_Form_Search
{
    protected $_location;
    public function setLocation($location)
    {
        $this -> _location = $location;
    }

    public function init()
    {
        // Add custom elements
        $this->getMemberTypeElement();
        $this->getDisplayNameElement();
        $this->getAdditionalOptionsElement();

        parent::init();

        $this->loadDefaultDecorators();
        $this->setMethod('get');
        $this->getDecorator('HtmlTag')->setOption('class', 'browsemembers_criteria');
    }

    public function getMemberTypeElement()
    {
        $multiOptions = array('' => ' ');
        $profileTypeFields = Engine_Api::_()->fields()->getFieldsObjectsByAlias($this->_fieldType, 'profile_type');
        if( count($profileTypeFields) !== 1 || !isset($profileTypeFields['profile_type']) ) return;
        $profileTypeField = $profileTypeFields['profile_type'];

        $options = $profileTypeField->getOptions();

        if( count($options) <= 1 ) {
            if( count($options) == 1 ) {
                $this->_topLevelId = $profileTypeField->field_id;
                $this->_topLevelValue = $options[0]->option_id;
            }
            return;
        }

        foreach( $options as $option ) {
            $multiOptions[$option->option_id] = $option->label;
        }

        $this->addElement('Select', 'category', array(
            'label' => 'Categories',
            'order' => -1000001,
            'class' =>
                'field_toggle' . ' ' .
                'parent_' . 0 . ' ' .
                'option_' . 0 . ' ' .
                'field_'  . $profileTypeField->field_id  . ' ',
            'onchange' => 'changeFields($(this));',
            'multiOptions' => $multiOptions,
        ));

        return $this->category;
    }

    public function getDisplayNameElement()
    {
        $this->addElement('Text', 'displayname', array(
            'label' => 'Name',
            'order' => -1000000,
            //'onkeypress' => 'return submitEnter(event)',
        ));
        return $this->displayname;
    }

    public function getAdditionalOptionsElement()
    {
        $subform = new Zend_Form_SubForm(array(
            'name' => 'extra',
            'order' => 1000000,
            'decorators' => array(
                'FormElements',
            )
        ));
        Engine_Form::enableForm($subform);

        $this->addElement('Text', 'location', array(
            'label' => 'Location',
            'decorators' => array(array(
                'ViewScript',
                array(
                    'viewScript' => '_location_search.tpl',
                    'viewModule' => 'socialcommerce',
                    'class' => 'form element',
                    'location' => $this->_location
                )
            )),
        ));

        $this->addElement('Text', 'within', array(
            'label' => 'Radius (mile)',
            'placeholder' => Zend_Registry::get('Zend_Translate')->_('Radius (mile)..'),
            'maxlength' => '60',
            'required' => false,
            'style' => "display: block",
            'validators' => array(
                array(
                    'Int',
                    true
                ),
                new Engine_Validate_AtLeast(0),
            ),
        ));

        $this->addElement('hidden', 'lat', array(
            'value' => '0',
            'order' => '98'
        ));

        $this->addElement('hidden', 'long', array(
            'value' => '0',
            'order' => '99'
        ));

        $this->addElement('Radio', 'sort_by', array(
            'label' => 'Browse By',
            'multiOptions' => array(
                'most_liked' => 'Most Liked',
                'most_viewed' => 'Most Viewed',
                'highest_sales' => 'Highest Sales',
                'biggest' => 'Biggest',
            )
        ));

        $subform->addElement('Button', 'done', array(
            'label' => 'Search',
            'type' => 'submit',
            'ignore' => true,
        ));

        $this->addSubForm($subform, $subform->getName());

        return $this;
    }
}