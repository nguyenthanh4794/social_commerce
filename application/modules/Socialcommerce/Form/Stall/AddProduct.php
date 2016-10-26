<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/23/2016
 * Time: 1:41 AM
 */
class Socialcommerce_Form_Stall_AddProduct extends Engine_Form
{
    public function init()
    {
        $user_level = Engine_Api::_()->user()->getViewer()->level_id;
        $user = Engine_Api::_()->user()->getViewer();

        // Init form
        $this
            ->setTitle('Add New Photos')
            ->setDescription('Add your product to this stall')
            ->setAttrib('id', 'form-upload')
            ->setAttrib('class', 'global_form')
            ->setAttrib('name', 'albums_create')
            ->setAttrib('enctype','multipart/form-data')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
        ;

        // Init name
        $this->addElement('Text', 'title', array(
            'label' => 'Name',
            'maxlength' => '40',
            'filters' => array(
                //new Engine_Filter_HtmlSpecialChars(),
                'StripTags',
                new Engine_Filter_Censor(),
                new Engine_Filter_StringLength(array('max' => '63')),
            )
        ));

        $this->addElement('Select', 'category', array(
            'label' => '*Category',
            'required' => true,
            'allowEmpty' => false,
        ));

        $this->addElement('Float', 'price', array(
            'label' => 'Price ('. Engine_Api::_() -> getApi('settings', 'core')->getSetting('payment.currency', 'USD') .')',
            'required' => true,
            'allowEmpty' => false,
            'value' => '0'
        ));

        $this->addElement('Textarea', 'description', array(
            'label' => 'Description',
            'description' => 'Add detail about your item (Optional)'
        ));

        $this->description->getDecorator('Description')->setOption('placement', 'APPEND');

        // Init file
        $this->addElement('FancyUpload', 'file');
        //$file = new Engine_Form_Element_FancyUpload('file');

        // Init submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Save',
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

    public function clearAlbum()
    {
        $this->getElement('album')->setValue(0);
    }

    public function saveValues()
    {
        $set_cover = true;
        $values = $this->getValues();
        $params = Array();

        $params['title'] = $values['title'];
        if (empty($params['title'])) {
            $params['title'] = "Untitled Product";
        }
        $params['description'] = $values['description'];
        $params['price'] = (float)$values['price'];
        $params['description'] = $values['description'];
        $params['file'] = $values['file'];

        $product = Engine_Api::_()->getDbtable('products', 'socialcommerce')->createRow();
        $product->setFromArray($params);
        $product->save();

        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

        foreach( $roles as $i => $role ) {
            $auth->setAllowed($product, $role, 'view', 1);
            $auth->setAllowed($product, $role, 'comment', 1);
        }

        // Do other stuff
        $count = 0;
        foreach( $values['file'] as $photo_id )
        {
            $photo = Engine_Api::_()->getItem("album_photo", $photo_id);
            if( !($photo instanceof Core_Model_Item_Abstract) || !$photo->getIdentity() ) continue;

            if( $set_cover )
            {
                $product->photo_id = $photo->file_id;
                $product->save();
                $set_cover = false;
            }

            $photo->order    = $photo_id;
            $photo->save();

            $count++;
        }

        return $product;
    }

}