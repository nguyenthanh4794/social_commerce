<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/17/2016
 * Time: 10:10 PM
 */
class Socialcommerce_Form_Admin_Product_Search extends Engine_Form
{
    public function init()
    {
        $this->clearDecorators()
            ->addDecorator('FormElements')
            ->addDecorator('Form')
            ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
            ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'));

        $this->setAttribs(array(
            'class' => 'global_form_box',
            'id' => 'filter_form',
            'method' => 'GET',
        ));

        //Feature Filter

        $this->addElement('Text', 'listing_title', array(
            'label' => 'Search Listing'
        ));

        $this->addElement('Text', 'owner', array(
            'label' => 'Owner',
        ));

        $this->addElement('Select', 'category', array(
            'label' => 'Category',
            'multiOptions' => array(
                'all' => 'All',
            ),
            'value' => 'all'
        ));

        $this->populateCategoryElement();

        $this->addElement('Select', 'approved_status', array(
            'label' => 'Approved Status',
            'multiOptions' => array(
                'all' => 'All',
                'pending' => 'Pending',
                'approved' => 'Approved',
                'denied' => 'Denied'
            ),
            'value' => 'all'
        ));

        $this->addElement('Select', 'status', array(
            'label' => 'Listing Status',
            'multiOptions' => array(
                'all' => 'All',
                'open' => 'Open',
                'closed' => 'Closed',
                'draft' => 'Draft',
                'expired' => 'Expired'
            ),
            'value' => 'all'
        ));

        $this->addElement('Select', 'featured', array(
            'label' => 'Featured',
            'multiOptions' => array(
                'all' => 'All',
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => 'all'
        ));

        $this->addElement('Button', 'search', array(
            'label' => 'Search',
            'type' => 'submit',
            'ignore' => true,
        ));

        $this->addElement('Hidden', 'order', array(
            'order' => 101,
            'value' => 'listing.listing_id'
        ));

        // Element: direction
        $this->addElement('Hidden', 'direction', array(
            'order' => 102,
            'value' => 'DESC',
        ));

        $this->search->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'buttons'))
            ->addDecorator('HtmlTag2', array('tag' => 'div'));
    }

    public function populateCategoryElement()
    {
        $table = Engine_Api::_()->getDbTable('categories', 'socialcommerce');
        $categories = $table->getCategories();
        unset($categories[0]);
        foreach ($categories as $item)
        {
            $this->category->addMultiOption($item['category_id'], str_repeat('--', $item[level] - 1) . $item['title']);
        }
    }
}