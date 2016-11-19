<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/8/2016
 * Time: 8:09 PM
 */
class Socialcommerce_Form_Payment_Shipping extends Engine_Form_Email
{
    public function init()
    {
        //Set Form Informations
        $this->setAttribs(array('class' => 'global_form', 'method' => 'post'))
            ->setTitle('Shipping Information')
            ->setDescription('SHIPPING_INFORMATION_DESCRIPTION');

        $this->addElement('text', 'fullname', array(
            'label' => 'Full Name',
            'maxlength' => 128,
            'required' => true,
            'filters' => array('StringTrim'),
        ));

        $emailElement = $this->addEmailElement(array(
            'label' => 'Email Address',
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(
                array('NotEmpty', true),
                array('EmailAddress', true),
            ),
            'filters' => array(
                'StringTrim'
            ),
            // fancy stuff
            'inputType' => 'email',
            'autofocus' => 'autofocus',
        ));
        $emailElement->getDecorator('Description')->setOptions(array('placement' => 'APPEND'));
        $emailElement->getValidator('NotEmpty')->setMessage('Please enter a valid email address.', 'isEmpty');
        $emailElement->getValidator('EmailAddress')->getHostnameValidator()->setValidateTld(false);

        $this->addElement('text', 'street', array(
            'label' => 'Address Line 1',
            'maxlength' => 128,
            'required' => true,
            'filters' => array('StringTrim'),
        ));

        $this->addElement('text', 'street2', array(
            'label' => 'Address Line 2',
            'maxlength' => 128,
            'filters' => array('StringTrim'),
        ));

        $this->addElement('text', 'city', array(
            'label' => 'City',
            'required' => true,
            'validators' => array(
                array('StringLength', false, array(1, 128)),
            ),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
            ),
        ));

        $this->addElement('text', 'region', array(
            'label' => 'State/Province',
            'required' => false,
            'validators' => array(
                array('StringLength', false, array(1, 128)),
            ),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
            ),
        ));

        $this->addElement('text', 'postcode', array(
            'label' => 'Zip/PostCode',
            'required' => true,
            'validators' => array(
                array('StringLength', false, array(1, 128)),
            ),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
            ),
        ));

        //Country
        $locale = Zend_Registry::get('Zend_Translate')->getLocale();
        $territories = Zend_Locale::getTranslationList('territory', $locale, 2);
        asort($territories);
        //if( !$this->isRequired() ) {
        $territories = array_merge(array(
            '' => '',
        ), $territories);
        //}
        $arr_countries = array();
        foreach ($territories as $key => $value) {
            $arr_countries[$value] = $value;;
        }

        $this->addElement('Select', 'country', array(
            'label' => 'Country',
            'multiOptions' => $arr_countries,
        ));

        $this->addElement('text', 'phone', array(
            'label' => 'Phone',
            'required' => true,
            'validators' => array(
                array('StringLength', false, array(1, 128)),
            ),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
            ),
        ));

        $this->addElement('Button', 'execute', array(
            'label' => 'Continue',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array(
                'ViewHelper',
            ),
        ));
        $order_id = '';
        if (Zend_Registry::isRegistered('order_id')) {
            $order_id = Zend_Registry::get('order_id');
        }
        $this->addElement('Button', 'addshippingaddress', array(
            'label' => 'Add Another Address',
            'type' => 'button',
            'onclick' => "javascript:en4.socialcommerce.shipping.addAnotherBox('" . $order_id . "')",
            'ignore' => true,
            'decorators' => array(
                'ViewHelper',
            ),
        ));

        // Element: cancel
        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => '',
            'onclick' => 'javascript:parent.Smoothbox.close();',
            'decorators' => array(
                'ViewHelper',
            ),

        ));
        // DisplayGroup: buttons
        $this->addDisplayGroup(array(
            'execute',
            'addshippingaddress',
            'cancel',
        ), 'buttons', array(
            'decorators' => array(
                'FormElements',
                'DivDivDivWrapper'
            ),
        ));
    }

    public function saveValues($order_id)
    {
        $viewer = Engine_Api::_()->user()->getViewer();
        $params = $this->getValues();
        unset($params['email_field']);

        $table = Engine_Api::_()->getDbTable('shippingaddresses', 'socialcommerce');
        $address = $table->createRow();

        $address->user_id = $viewer->getIdentity();
        $address->order_id = $order_id;
        $address->value = json_encode($params);
        $address->save();

        return $address;
    }
}