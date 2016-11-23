<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/22/2016
 * Time: 11:33 PM
 */
class Socialcommerce_Form_Seller_SearchBuyActivities extends Engine_Form
{
    public function init()
    {
        $view = Zend_Registry::get('Zend_View');
        $this->setMethod('get');
        $this->addElement('Text', 'text', array(
            'label' => 'Product Name',
        ));

        // Element Categories
        $this->addElement('Select', 'category', array(
            'placeholder' => 'Category',
            'multiOptions' => array(
                'all' => 'All categories'
            ),
        ));
        $this->populateCategoryElement();

        // Element From and To Date
        $date_validate = new Zend_Validate_Date("YYYY-MM-dd");
        $date_validate->setMessage("Please pick a valid day (yyyy-mm-dd)", Zend_Validate_Date::FALSEFORMAT);

        // From Date
        $start = new Engine_Form_Element_Text('start_date');
        $start -> setLabel('Bought (From)');
        $start -> setAttribs(array('placeholder' => 'yyyy-mm-dd'));
        $start -> setAllowEmpty(true);
        $start -> addValidator($date_validate);
        $this -> addElement($start);

        // To Date
        $end = new Engine_Form_Element_Text('to_date');
        $end -> setLabel('Bought (To)');
        $end -> setAttribs(array('placeholder' => 'yyyy-mm-dd'));
        $end -> setAllowEmpty(true);
        $end -> addValidator($date_validate);
        $this -> addElement($end);

        $orderStatuses = array(
            '' => 'All',
            'paid' => 'Paid',
            'unpaid' => 'Unpaid',
            'completed' => 'Completed',
            'refunded' => 'Refunded'
        );
        $this->addElement('Select', 'order_status', array(
            'label' => 'Order Status',
            'multiOptions' => $orderStatuses
        ));

        $this->addElement('Button', 'search', array(
            'label' => 'Search',
            'type' => 'submit'
        ));
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