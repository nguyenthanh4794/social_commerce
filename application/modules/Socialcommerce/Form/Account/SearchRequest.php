<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 11:41 PM
 */
class Socialcommerce_Form_Account_SearchRequest extends Engine_Form
{
    public function init(){


        $date_validate = new Zend_Validate_Date("YYYY-MM-dd");
        $date_validate->setMessage("Please pick a valid day (yyyy-mm-dd)", Zend_Validate_Date::FALSEFORMAT);


        // From Date
        $request_from = new Engine_Form_Element_Text('request_from');
        $request_from -> setAttribs(array('placeholder' => 'From'));
        $request_from -> setAllowEmpty(true);
        $request_from -> addValidator($date_validate);
        $request_from -> setLabel('Request time');
        $this -> addElement($request_from);

        // To Date
        $request_to = new Engine_Form_Element_Text('request_to');
        $request_to -> setAttribs(array('placeholder' => 'To'));
        $request_to -> setAllowEmpty(true);
        $request_to -> addValidator($date_validate);

        $this -> addElement($request_to);



        // Response time

        // From Date
        $response_from = new Engine_Form_Element_Text('response_from');
        $response_from -> setLabel('Response time');
        $response_from -> setAttribs(array('placeholder' => 'From'));
        $response_from -> setAllowEmpty(true);
        $response_from -> addValidator($date_validate);
        $this -> addElement($response_from);

        // To Date
        $response_to = new Engine_Form_Element_Text('response_to');
        $response_to -> setAttribs(array('placeholder' => 'To'));
        $response_to -> setAllowEmpty(true);
        $response_to -> addValidator($date_validate);

        $this -> addElement($response_to);


        $this->addElement('Select', 'status', array(
            'label' => 'Status',
            'multiOptions' => array(
                '' => 'All',
                'approved' => 'Approved',
                'waiting' => 'Waiting',
                'denied' => 'Denied'
            )
        ));


        $this->addElement('Button', 'search', array(
            'label' => 'Search',
            'type' => 'submit'
        ));

    }
}