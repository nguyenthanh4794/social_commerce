<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/13/2016
 * Time: 10:49 PM
 */
class Socialcommerce_ProductController extends Core_Controller_Action_Standard
{
    public function indexAction()
    {
        $this->_helper->content->setEnable();
    }

    public function createAction()
    {
        if( isset($_GET['ul']) || isset($_FILES['Filedata']) ) return $this->_forward('upload-photo', null, null, array('format' => 'json'));

        if( !$this->_helper->requireUser()->isValid() ) return;
//        if( !$this->_helper->requireAuth()->setAuthParams('socialcommerce_stall', null, 'create')->isValid()) return;

        // Render
        $this->_helper->content
            //->setNoRender()
            ->setEnabled()
        ;
        $this -> view -> form = $form = new Socialcommerce_Form_Product_Create();
        $categories = Engine_Api::_()->getDbTable('categories', 'socialcommerce')->getCategoriesAssoc();
        $form->category->setMultiOptions($categories);

        // If not post or form not valid, return
        if( !$this->getRequest()->isPost() ) {
            return;
        }

        if( !$form->isValid($this->getRequest()->getPost()) ) {
            return;
        }

        die(print_r($form->getValues()));
    }

    public function uploadPhotoAction()
    {
        if( !$this->_helper->requireUser()->checkRequire() )
        {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Max file size limit exceeded (probably).");
            return;
        }

        if( !$this->getRequest()->isPost() )
        {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid request method");
            return;
        }

        $values = $this->getRequest()->getPost();
        if( empty($values['Filename']) )
        {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("No file");
            return;
        }

        if( !isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name']) )
        {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid Upload");
            return;
        }
        $table = Engine_Api::_()->getDbtable('adphotos', 'core');
        $db = $table->getAdapter();
        $db->beginTransaction();

        try
        {
            $viewer = Engine_Api::_()->user()->getViewer();

            $params = array(
                'owner_type' => 'user',
                'owner_id' => $viewer->getIdentity()
            );
            $photo_id = Engine_Api::_()->getApi('Ad', 'core')->createPhoto($params, $_FILES['Filedata'])->adphoto_id;

            $this->view->status = true;
            $this->view->name = $_FILES['Filedata']['name'];
            $this->view->photo_id = $photo_id;
            $this->view->photo_url = Engine_Api::_()->getItem('core_adphoto', $photo_id)->getPhotoUrl();


            $db->commit();
        }

        catch( Exception $e )
        {
            $db->rollBack();
            $this->view->status = false;
            $this->view->error = 'An error occurred.'.$e;
            // throw $e;
            return;
        }
    }
}