<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/15/2016
 * Time: 12:37 PM
 */
class Socialcommerce_Form_Search extends Engine_Form
{
    protected $_location;
    public function setLocation($location)
    {
        $this -> _location = $location;
    }

    public function init()
    {
        $this->setAttribs(array('class' => 'global_form', 'id' => 'filter_form'));
        $this->setMethod('GET');

        $this->addElement('Text', 'keyword', array(
            'label' => 'Keyword',
        ));

        // Category
        $this->addElement('Select', 'category', array(
            'label' => 'Category',
            'multiOptions' => array(
                'all' => 'All categories',
            )
        ));
        $this->populateCategories();

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

        $this->addElement('Hidden', 'page', array(
            'order' => 100
        ));

        $this->addElement('Button', 'done', array(
            'label' => 'Search',
            'type' => 'submit',
            'ignore' => true,
        ));
    }

    public function populateCategories()
    {
        $table = Engine_Api::_()->getDbTable('categories', 'socialcommerce');
        $categories = $table->getCategories();

        unset($categories[0]);
        foreach ($categories as $item)
        {
            $this->category->addMultiOption($item['category_id'], str_repeat('--', $item['level'] - 1) . $item['title']);
        }
    }
}