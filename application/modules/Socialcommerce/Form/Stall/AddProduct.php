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
            ->setDescription('Choose photos on your computer to add to this album.')
            ->setAttrib('id', 'form-upload')
            ->setAttrib('name', 'albums_create')
            ->setAttrib('enctype','multipart/form-data')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
        ;

        // Init name
        $this->addElement('Text', 'title', array(
            'label' => 'Album Title',
            'maxlength' => '40',
            'filters' => array(
                //new Engine_Filter_HtmlSpecialChars(),
                'StripTags',
                new Engine_Filter_Censor(),
                new Engine_Filter_StringLength(array('max' => '63')),
            )
        ));

        // Init file
        $this->addElement('FancyUpload', 'file');
        //$file = new Engine_Form_Element_FancyUpload('file');

        // Init submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Photos',
            'type' => 'submit',
        ));
    }

    public function clearAlbum()
    {
        $this->getElement('album')->setValue(0);
    }

    public function saveValues()
    {
        $set_cover = false;
        $values = $this->getValues();
        die($values);
        $params = Array();
        if ((empty($values['owner_type'])) || (empty($values['owner_id'])))
        {
            $params['owner_id'] = Engine_Api::_()->user()->getViewer()->user_id;
            $params['owner_type'] = 'user';
        }
        else
        {
            $params['owner_id'] = $values['owner_id'];
            $params['owner_type'] = $values['owner_type'];
            throw new Zend_Exception("Non-user album owners not yet implemented");
        }

        $params['title'] = $values['title'];
        if (empty($params['title'])) {
            $params['title'] = "Untitled Album";
        }
        $params['category_id'] = (int) @$values['category_id'];
        $params['description'] = $values['description'];
        $params['search'] = $values['search'];

        $product = Engine_Api::_()->getDbtable('products', 'socialcommerce')->createRow();
        $product->setFromArray($params);
        $product->save();

        // Do other stuff
        $count = 0;
        foreach( $values['file'] as $photo_id )
        {
            $photo = Engine_Api::_()->getItem("album_photo", $photo_id);
            if( !($photo instanceof Core_Model_Item_Abstract) || !$photo->getIdentity() ) continue;

            if( $set_cover )
            {
                $product->photo_id = $photo_id;
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