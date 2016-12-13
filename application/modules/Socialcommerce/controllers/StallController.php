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

            if($oStall && !Engine_Api::_()->core()->hasSubject('socialcommerce_stall')) {
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

        $request = $this->getRequest()->getPost();

        // Uploading a new photo
        if( $form->Filedata->getValue() !== null ) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

            try {
                $fileElement = $form->Filedata;
                $oStall->setPhoto($fileElement, 'photo_id');
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

        else if( $form->FileCoverdata->getValue() !== null ) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

            try {
                $fileElement = $form->FileCoverdata;
                $oStall->setPhoto($fileElement, 'cover_id');
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

        else if (isset($request['done'])) {
            return $this -> _forward('success', 'utility', 'core', array(
                'parentRedirect' => Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array(
                    'module' => 'socialcommerce',
                    'controller' => 'stall',
                    'action' => 'profile',
                    'id' => $oStall -> getIdentity(),
                    'slug' => $oStall->title,
                ), 'socialcommerce_profile', true),
                'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Your stall has been successfully created.'))
            ));
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
        $stall->cover_id = 0;
        $stall->save();
        $db->commit();

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_("Your stall's photo and cover has been removed.");

        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => true,
            'parentRefresh' => true,
            'messages' => array(Zend_Registry::get('Zend_Translate')->_("Your stall's photo and cover has been removed."))
        ));
    }

    public function profileAction()
    {
        if(!Engine_Api::_()->core()->hasSubject())
        {
            return $this->_helper->requireSubject()->forward();
        }
        $subject = Engine_Api::_()->core()->getSubject();

        if (!$subject) {
            return $this->_helper->requireSubject()->forward();
        }
        // Check authorization to view business.
        if (!$subject->isViewable()) {
            return $this -> _helper -> requireAuth() -> forward();
        }
        $viewer = Engine_Api::_()->user()->getViewer();

//        if(!$viewer -> isAdmin() && !$viewer -> isSelf($subject -> getOwner()))
//        {
//            return $this -> _helper -> requireAuth() -> forward();
//        }

        //$subject -> view_count += 1;
        $subject -> save();
        // Render
        $this->_helper->content
            ->setNoRender()
            ->setEnabled()
        ;
    }

    public function editInfoAction()
    {
        if(!Engine_Api::_()->core()->hasSubject())
        {
            return $this->_helper->requireSubject()->forward();
        }

        $this->view-> stall = $stall = Engine_Api::_()->core()->getSubject('socialcommerce_stall');

        $this->view->form = $form = new Socialcommerce_Form_Stall_EditInfo();

        $form -> populate($stall->toArray());

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
            $values = $form->getValues();
            $stall->setFromArray($values);
            $stall->modified_date = date('Y-m-d H:i:s');
            $stall->save();

//            // insert new activity if blog is just getting published
//            $action = Engine_Api::_()->getDbtable('actions', 'activity')->getActionsByObject($stall);
//            if( count($action->toArray()) <= 0) {
//                $viewer = Engine_Api::_()->user()->getViewer();
//                $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $stall, 'stall_new');
//                // make sure action exists before attaching the blog to the activity
//                if( $action != null ) {
//                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $stall);
//                }
//            }
//
//            // Rebuild privacy
//            $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
//            foreach( $actionTable->getActionsByObject($stall) as $action ) {
//                $actionTable->resetActivityBindings($action);
//            }

            $db->commit();

        }
        catch( Exception $e )
        {
            $db->rollBack();
            throw $e;
        }

        return $this -> _forward('success', 'utility', 'core', array(
            'parentRedirect' => Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array(
                'module' => 'socialcommerce',
                'controller' => 'stall',
                'action' => 'profile',
                'id' => $stall -> getIdentity(),
                'slug' => $stall->title,
            ), 'socialcommerce_profile', true),
            'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Your stall has been successfully updated.'))
        ));
    }

    public function addProductAction()
    {
        if( isset($_GET['ul']) )
            return $this->_forward('upload-photo', null, null, array('format' => 'json'));

        if( isset($_FILES['Filedata']) )
            $_POST['file'] = $this->uploadPhotoAction();

        if(!Engine_Api::_()->core()->hasSubject())
        {
            return $this->_helper->requireSubject()->forward();
        }

        $this->view-> stall = $stall = Engine_Api::_()->core()->getSubject('socialcommerce_stall');

        $this->view->form = $form = new Socialcommerce_Form_Stall_AddProduct();

        $categories = Engine_Api::_()->getDbTable('categories', 'socialcommerce')->getCategoriesAssoc(2);
        $form->category->setMultiOptions($categories);

        if( !$this->getRequest()->isPost() )
        {
            return;
        }

        if( !$form->isValid($this->getRequest()->getPost()) )
        {
            return;
        }

        $db = Engine_Api::_()->getItemTable('socialcommerce_product')->getAdapter();
        $db->beginTransaction();

        try
        {
            $product = $form->saveValues();

            $product->stall_id = $stall->getIdentity();
            $product->owner_type = 'user';
            $product->owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();
            $product->save();

            $viewer = Engine_Api::_()->user()->getViewer();

            // Add activity only if blog is published
            $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $stall, 'product_new');

            // make sure action exists before attaching the blog to the activity
            if( $action ) {
                Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $product);
            }

            $db->commit();
        }
        catch( Exception $e )
        {
            $db->rollBack();
            throw $e;
        }

        return $this -> _forward('success', 'utility', 'core', array(
            'parentRedirect' => Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array(
                'module' => 'socialcommerce',
                'controller' => 'stall',
                'action' => 'profile',
                'id' => $stall -> getIdentity(),
                'slug' => $stall->title,
            ), 'socialcommerce_profile', true),
            'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Your product has been successfully added.'))
        ));
    }

    public function uploadPhotoAction()
    {
        if( !$this->_helper->requireAuth()->setAuthParams('album', null, 'create')->isValid() ) return;

        if( !$this->_helper->requireUser()->checkRequire() )
        {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('Max file size limit exceeded (probably).');
            return;
        }

        if( !$this->getRequest()->isPost() )
        {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
            return;
        }

        $values = $this->getRequest()->getPost();
        if( empty($values['Filename']) && !isset($_FILES['Filedata']))
        {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('No file');
            return;
        }

        if( !isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name']) )
        {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid Upload');
            return;
        }

        $db = Engine_Api::_()->getDbtable('photos', 'album')->getAdapter();
        $db->beginTransaction();

        try
        {
            $viewer = Engine_Api::_()->user()->getViewer();
            $stall = Engine_Api::_()->core()->getSubject('socialcommerce_stall');
            $photoTable = Engine_Api::_()->getDbtable('photos', 'album');
            $photo = $photoTable->createRow();
            $photo->setFromArray(array(
                'owner_type' => 'socialcommerce_product',
                'owner_id' => $viewer->getIdentity(),
                'item_id' => $stall->getIdentity()
            ));
            $photo->save();

            $photo->order = $photo->photo_id;
            $photo->setPhoto($_FILES['Filedata']);
            $photo->save();

            $this->view->status = true;
            $this->view->name = $_FILES['Filedata']['name'];
            $this->view->photo_id = $photo->photo_id;

            $db->commit();
            return $photo->photo_id;

        } catch( Album_Model_Exception $e ) {
            $db->rollBack();
            $this->view->status = false;
            $this->view->error = $this->view->translate($e->getMessage());
            throw $e;
            return;

        } catch( Exception $e ) {
            $db->rollBack();
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
            throw $e;
            return;
        }
    }
}