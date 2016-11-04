<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/30/2016
 * Time: 12:09 PM
 */
class Socialcommerce_Form_SellerInfo extends Engine_Form
{
    public function init()
    {
        // Init form
        $this
            ->setTitle('Add Seller Information')
            ->setDescription('Tell us your seller information')
            ->setAttrib('class', 'global_form')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
        ;

        // Init address
        $this->addElement('Text', 'address', array(
            'label' => 'Street address',
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
            )
        ));

        $this->addElement('Text', 'city', array(
            'label' => 'City / Town',
        ));

        $locale = Zend_Registry::get('Zend_Translate')->getLocale();
        $territories = Zend_Locale::getTranslationList('territory', $locale, 2);
        asort($territories);
        //if( !$this->isRequired() ) {
        $territories = array_merge(array(
            '' => '',
        ), $territories);
        //}

        $this->addElement('Select', 'country', array(
            'label' => 'Country',
            'multiOptions' => $territories,
        ));

        $this->addElement('Text', 'zip_code', array(
            'label' => 'ZIP / Postal Code',
        ));

        $this->addElement('Text', 'business_name', array(
            'label' => 'Choose your business display name',
            'description' => 'What is a business display name?'
        ));

        //Web address
        $this->addElement('Text', 'web_address', array(
            'label' => 'Your website URL (optional)',
            'validators' => array(
                array('StringLength', false, array(1, 64)),
                array('Regex', true, array('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i')),
            ),
            'placeholder' => 'www.google.com',
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
            ),
            'class' => 'btn_form_inline',
        ));
        $this -> web_address -> getDecorator("Description") -> setOption("placement", "append") -> setEscape(FALSE);

        $this->addElement('Text', 'mobile', array(
            'label' => 'Mobile Number',
            'description' => 'E.g. + 123456789',
        ));

        // Init submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Next',
            'ignore' => true,
            'decorators' => array('ViewHelper'),
            'type' => 'submit'
        ));

        $this->addElement('Cancel', 'cancel', array(
            'prependText' => ' or ',
            'label' => 'cancel',
            'link' => true,
            'href' => '',
            'onclick' => 'parent.Smoothbox.close();',
            'decorators' => array(
                'ViewHelper'
            ),
        ));

        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
    }

    public function postEntry()
    {
        $values = $this->getValues();
        $user = Engine_Api::_()->user()->getViewer();

        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try{
            // Transaction
            $table = Engine_Api::_()->getDbtable('accounts', 'socialcommerce');
            $params = array_merge(array(
                'user_id' => $user->getIdentity()
            ), $values);
            // insert the blog entry into the database
            $row = $table->createRow();
            $row->setFromArray($params);
            $row->save();

            $attachment = Engine_Api::_()->getItem($row->getType(), $row->getIdentity());
            $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($user, $row, 'account_new');
            if ($action) {
                Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $attachment);
            }
            $db->commit();
        }
        catch( Exception $e )
        {
            $db->rollBack();
            throw $e;
        }
    }

}