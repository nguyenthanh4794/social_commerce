<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/13/2016
 * Time: 9:07 PM
 */
class Socialcommerce_StallController extends Core_Controller_Action_Standard
{
    public function init()
    {
        $iStallId = $this -> _getParam('stall_id', $this -> _getParam('id', null));
        if($iStallId) {
            $oStall = Engine_Api::_() -> getItem('socialcommerce_stall', $iStallId);
            if($oStall) {
                Engine_Api::_() -> core() -> setSubject($oStall);

                if (!$this -> _helper -> requireAuth -> setAuthParams($oStall, null, 'view') -> isValid()) {
                    return;
                }
            }
        }
    }

    public function browseAction()
    {
        // Render
        $this->_helper->content
            //->setNoRender()
            ->setEnabled()
        ;
    }

    public function createStepOneAction()
    {
        // Render
        $this->_helper->content
            //->setNoRender()
            ->setEnabled()
        ;

        // Return if guest try to access to create link.
        if (!$this -> _helper -> requireUser -> isValid())
            return;

        $this->view->form = $form = new Socialcommerce_Form_Stall_CreateStepOne();
        $categories = Engine_Api::_()->getDbTable('categories', 'socialcommerce')->getCategoriesAssoc();
        $form->category->setMultiOptions($categories);

        if( !$this->getRequest()->isPost() ) {
            return;
        }

        if( !$form->isValid($this->getRequest()->getPost()) ) {
            return;
        }

        $table = Engine_Api::_()->getDbtable('stalls', 'socialcommerce');
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
            $viewer = Engine_Api::_()->user()->getViewer();
            $values = array_merge($form->getValues(), array(
                'owner_type' => $viewer->getType(),
                'owner_id' => $viewer->getIdentity(),
            ));

            $stall = $table->createRow();
            $stall->setFromArray($values);
            $stall->save();

            // Auth
            $auth = Engine_Api::_()->authorization()->context;
            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

            if( empty($values['auth_view']) ) {
                $values['auth_view'] = 'everyone';
            }

            if( empty($values['auth_comment']) ) {
                $values['auth_comment'] = 'everyone';
            }

            $viewMax = array_search($values['auth_view'], $roles);
            $commentMax = array_search($values['auth_comment'], $roles);

            foreach( $roles as $i => $role ) {
                $auth->setAllowed($stall, $role, 'view', ($i <= $viewMax));
                $auth->setAllowed($stall, $role, 'comment', ($i <= $commentMax));
            }

            // Add activity only if blog is published
            $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $stall, 'stall_new');

            // make sure action exists before attaching the blog to the activity
            if( $action ) {
                Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $stall);
            }

            // Commit
            $db->commit();

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        return $this->_helper->redirector->gotoRoute(array('action' => 'create-step-two', 'id' => $stall->getIdentity()));
    }

    public function createStepTwoAction()
    {
        // Render
        $this -> _helper -> content -> setEnabled();

        if (!$this -> _helper -> requireSubject('socialcommerce_stall') -> isValid()) return;

        $this->view->stall = $oStall = Engine_Api::_() -> core() -> getSubject();

        $this->view->form = $form = new Socialcommerce_Form_Stall_CreateStepTwo();

        if( empty($oStall->photo_id) ) {
            $form->removeElement('remove');
        }

        if( !$this->getRequest()->isPost() ) {
            return;
        }

        if( !$form->isValid($this->getRequest()->getPost()) ) {
            return;
        }

        // Uploading a new photo
        if( $form->Filedata->getValue() !== null ) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

            try {
                $fileElement = $form->Filedata;
                $oStall->setPhoto($fileElement);
                $oStall->save();
                $db->commit();
            }

                // If an exception occurred within the image adapter, it's probably an invalid image
            catch( Engine_Image_Adapter_Exception $e )
            {
                $db->rollBack();
                $form->addError(Zend_Registry::get('Zend_Translate')->_('The uploaded file is not supported or is corrupt.'));
            }

                // Otherwise it's probably a problem with the database or the storage system (just throw it)
            catch( Exception $e )
            {
                $db->rollBack();
                throw $e;
            }
        }

        // Resizing a photo
        else if( $form->getValue('coordinates') !== '' ) {
            $storage = Engine_Api::_()->storage();

            $iProfile = $storage->get($oStall->photo_id, 'thumb.profile');
            $iSquare = $storage->get($oStall->photo_id, 'thumb.icon');

            // Read into tmp file
            $pName = $iProfile->getStorageService()->temporary($iProfile);
            $iName = dirname($pName) . '/nis_' . basename($pName);

            list($x, $y, $w, $h) = explode(':', $form->getValue('coordinates'));

            $image = Engine_Image::factory();
            $image->open($pName)
                ->resample($x+.1, $y+.1, $w-.1, $h-.1, 48, 48)
                ->write($iName)
                ->destroy();

            $iSquare->store($iName);

            // Remove temp files
            @unlink($iName);
        }
    }

    public function removePhotoAction()
    {
        // Get form
        $this->view->form = $form = new Socialcommerce_Form_Stall_RemovePhoto();

        if( !$this->getRequest()->isPost() || !$form->isValid($this->getRequest()->getPost()) )
        {
            return;
        }


        $stall = Engine_Api::_()->core()->getSubject();
        $db = Engine_Db_Table::getDefaultAdapter();
        $stall->photo_id = 0;
        $stall->save();
        $db->commit();

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_("Your stall's photo has been removed.");

        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => true,
            'parentRefresh' => true,
            'messages' => array(Zend_Registry::get('Zend_Translate')->_("Your stall's photo has been removed."))
        ));
    }
}