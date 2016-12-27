<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/6/2016
 * Time: 9:54 PM
 */
class Socialcommerce_PhotoController extends Core_Controller_Action_Standard
{
    public function init()
    {
        if (!Engine_Api::_()->core()->hasSubject()) {
            if (0 !== ($listing_id = (int)$this->_getParam('listing_id')) &&
                null !== ($listing = Engine_Api::_()->getItem('socialcommerce_product', $listing_id))
            ) {
                Engine_Api::_()->core()->setSubject($listing);
            }
        }
    }

    public function uploadAction()
    {
        $listing = Engine_Api::_()->core()->getSubject();
        if (isset($_GET['ul']) || isset($_FILES['Filedata'])) return $this->_forward('upload-photo', null, null, array('format' => 'json', 'listing_id' => (int)$listing->getIdentity()));

        $viewer = Engine_Api::_()->user()->getViewer();
        $listing = Engine_Api::_()->getItem('socialcommerce_product', (int)$listing->getIdentity());
        if ($listing->owner_id == $viewer->getIdentity()) {
            $this->view->canUpload = true;
        }

        $this->view->listing_id = $listing->getIdentity();
        $this->view->form = $form = new Socialcommerce_Form_Photo_Upload();
        $form->listing_id->setValue($listing->getIdentity());

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }
        $publish = $this->_getParam('publish');
        // Process
        $table = Engine_Api::_()->getItemTable('photo');
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
            $values = $form->getValues();
            $set_cover = (Engine_Api::_()->getItem("album_photo", $listing->photo_id) === null);
            // Add action and attachments

            foreach( $values['file'] as $photo_id )
            {
                $photo = Engine_Api::_()->getItem("album_photo", $photo_id);
                if( !($photo instanceof Core_Model_Item_Abstract) || !$photo->getIdentity() ) continue;

                if ($set_cover)
                {
                    $listing->photo_id = $photo->file_id;
                    $listing->save();
                    $set_cover = false;
                }

                $photo->item_id = $listing->getIdentity();
                $photo->order    = $photo_id;
                $photo->save();
            }

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        return $this->_helper->redirector->gotoRoute(array('controller' => 'photo', 'action' => 'manage', 'listing_id' => $listing->getIdentity()), 'socialcommerce_general', true);
    }

    public function manageAction()
    {
//        // Authorization
        if (!$this->_helper->requireUser()->isValid())
            return;

        if (!$this->_helper->requireSubject('socialcommerce_product')->isValid()) {
            return;
        }

        // GET LISTING
        $listing = Engine_Api::_()->core()->getSubject();

        // CHECK FOR EDIT PERMISSION
        if (!$listing->allowAction('edit')) {
            return $this->_helper->requireAuth->forward();
        }

        $this->view->listing = $listing;
        // MAIN FORM
        $this->view->form = $form = new Socialcommerce_Form_Product_ManagePhotos();

        // GET LISTING ALBUM
        $this->view->paginator = $paginator = Zend_Paginator::factory($listing->getProductPhotoSelect());

        foreach ($paginator as $photo) {
            // GET SUBFORM
            $subform = new Socialcommerce_Form_Photo_Edit();
            if ($photo->file_id == $listing->photo_id) {
                $subform->removeElement('delete');
            }
            $subform->populate($photo->toArray());
            $form->addSubForm($subform, $photo->getGuid());
            $form->cover->addMultiOption($photo->getIdentity(), $photo->getIdentity());
        }

        // Check post/form
        if (!$this->getRequest()->isPost()) {
            return;
        }

        $post = $this->getRequest()->getPost();

        if (!$form->isValid($post))
            return;
        $values = $form->getValues();
        $cover = $values['cover'];
        // Process
        foreach ($paginator as $photo) {
            $subform = $form->getSubForm($photo->getGuid());
            $subValues = $subform->getValues();
            $subValues = $subValues[$photo->getGuid()];
            unset($subValues['photo_id']);

            if (isset($cover) && $cover == $photo->photo_id) {
                $listing->photo_id = $photo->file_id;
                $listing->save();
            }

            if (isset($subValues['delete']) && $subValues['delete'] == '1') {
                if ($listing->photo_id == $photo->file_id) {
                    $listing->photo_id = 0;
                    $listing->save();
                }
                $photo->delete();
            } else if (!empty($subValues) && is_array($subValues)) {
                $photo->setFromArray($subValues);
                $photo->save();
            }
        }

        return $this->_helper->redirector->gotoRoute(array('product_id' => $listing->getIdentity()), 'socialcommerce_product_profile', true);
    }

    public function uploadPhotoAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if (!$this->_helper->requireUser()->checkRequire()) {
            $status = false;
            $error = Zend_Registry::get('Zend_Translate')->_('Max file size limit exceeded (probably).');
            return $this->getResponse()->setBody(Zend_Json::encode(array('files' => array(0 => array('status' => $status, 'error' => $error)))));
        }
        if (!$this->getRequest()->isPost()) {
            $status = false;
            $error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
            return $this->getResponse()->setBody(Zend_Json::encode(array('files' => array(0 => array('status' => $status, 'error' => $error)))));
        }

        $listing = Engine_Api::_()->getItem('socialcommerce_product', (int)$_REQUEST['listing_id']);


        // @todo check auth
        //$deal

        if (empty($_FILES['files'])) {
            $status = false;
            $error = Zend_Registry::get('Zend_Translate')->_('No file');
            return $this->getResponse()->setBody(Zend_Json::encode(array('files' => array(0 => array('status' => $status, 'name' => $error)))));
        }

        $name = $_FILES['files']['name'][0];
        $type = explode('/', $_FILES['files']['type'][0]);
        if (!$_FILES['files'] || !is_uploaded_file($_FILES['files']['tmp_name'][0]) || $type[0] != 'image') {
            $status = false;
            $error = Zend_Registry::get('Zend_Translate')->_('Invalid Upload');
            return $this->getResponse()->setBody(Zend_Json::encode(array('files' => array(0 => array('status' => $status, 'error' => $error, 'name' => $name)))));
        }

        $photoTable = Engine_Api::_()->getDbtable('photos', 'album');
        $db = $photoTable->getAdapter();
        $db->beginTransaction();
        try {
            $viewer = Engine_Api::_()->user()->getViewer();

            $params = array(
                // We can set them now since only one album is allowed
                'owner_type' => 'socialcommerce_product',
                'owner_id' => $viewer->getIdentity(),
                'item_id' => $listing->getIdentity(),
            );

            $photo = $photoTable->createRow();
            $photo->setFromArray($params);

            $temp_file = array('type' => $_FILES['files']['type'][0], 'tmp_name' => $_FILES['files']['tmp_name'][0], 'name' => $_FILES['files']['name'][0]);

            $photo->setPhoto($temp_file);
            $photo->save();

            if (!$listing->photo_id) {
                $listing->photo_id = $photo->getIdentity();
                $listing->save();
            }

            $db->commit();
            $status = true;
            $name = $_FILES['files']['name'][0];

            return $this->getResponse()->setBody(Zend_Json::encode(array('files' => array(0 => array('status' => $status, 'name' => $name, 'photo_id' => $photo->getIdentity())))));
        } catch (Exception $e) {
            $db->rollBack();
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
            // throw $e;
            return;
        }
    }

    public function removeAction()
    {
        $viewer = Engine_Api::_()->user()->getViewer();

        $photo_id = (int)$this->_getParam('photo_id');
        $photo = Engine_Api::_()->getItem('album_photo', $photo_id);

        $db = $photo->getTable()->getAdapter();
        $db->beginTransaction();

        try {
            $photo->delete();

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function deletePhotoAction()
    {
        $photo = Engine_Api::_()->getItem('album_photo', $this->getRequest()->getParam('photo_id'));

        if (!$photo) {
            $this->view->success = false;
            $this->view->error = $this->view->translate('Not a valid photo');
            $this->view->post = $_POST;
            return;
        }
        // Process
        $db = Engine_Api::_()->getDbtable('photos', 'album')->getAdapter();
        $db->beginTransaction();

        try {
            $photo->delete();

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}