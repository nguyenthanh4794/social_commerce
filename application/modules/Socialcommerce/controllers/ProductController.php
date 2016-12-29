<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/13/2016
 * Time: 10:49 PM
 */
class Socialcommerce_ProductController extends Core_Controller_Action_Standard
{
    public function init()
    {
        $iProductId = $this -> _getParam('product_id', $this -> _getParam('id', null));

        if($iProductId) {
            $oProduct = Engine_Api::_() -> getItem('socialcommerce_product', $iProductId);

            if($oProduct && !Engine_Api::_()->core()->hasSubject('socialcommerce_product')) {
                Engine_Api::_() -> core() -> setSubject($oProduct);

//                if (!$this -> _helper -> requireAuth -> setAuthParams($oProduct, null, 'view') -> isValid()) {
//                    return;
//                }
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

    public function indexAction()
    {
        $this->_helper->content->setEnable();
    }

    public function editAction()
    {
        $this->_helper->content
            //->setNoRender()
            ->setEnabled()
        ;
        if (!$this->_helper->requireAuth()->setAuthParams('socialcommerce_product', null, 'view')->isValid()) {
            return $this->_helper->requireAuth()->forward();
        }

        if(!Engine_Api::_()->core()->hasSubject())
        {
            return $this->_helper->requireSubject()->forward();
        }

        $this->view->product = $subject = Engine_Api::_()->core()->getSubject();

        if (!$subject) {
            return $this->_helper->requireSubject()->forward();
        }

        $user = Engine_Api::_()->user()->getViewer();

        $this -> view -> form = $form = new Socialcommerce_Form_Stall_AddProduct();
        $categories = Engine_Api::_()->getDbTable('categories', 'socialcommerce')->getCategoriesAssoc();
        $form->category->setMultiOptions($categories);
        $form->populate($subject->toArray());

        // If not post or form not valid, return
        if( !$this->getRequest()->isPost() ) {
            return;
        }

        if( !$form->isValid($this->getRequest()->getPost()) ) {
            return;
        }

        $table = Engine_Api::_()->getDbTable('products', 'socialcommerce');
        // Begin transaction
        $db = $table -> getAdapter();
        $db -> beginTransaction();

        try {
            $values = $form->getValues();
            $product = $table -> createRow();

            $product -> setFromArray($values);
            $product -> owner_id = $user -> getIdentity();

            if (!empty($values['main_photo'])) {
                $product->setPhoto($form->main_photo);
            }
            $product -> save();

            // Auth
            $auth = Engine_Api::_()->authorization()->context;
            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

            foreach ($roles as $i => $role)
            {
                $auth->setAllowed($product, $role, 'view', 1);
                $auth->setAllowed($product, $role, 'comment', 1);
                $auth->setAllowed($product, $role, 'submission', 1);
            }

            $db -> commit();
        } catch (Exception $e) {
            $db -> rollBack();
            throw $e;
        }

        return $this -> _forward('success', 'utility', 'core', array(
            'parentRedirect' => Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array(
                'product_id' => $product -> getIdentity(),
                'slug' => $product->getTitle(),
            ), 'socialcommerce_product_profile', true),
            'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Your product has been successfully updated.'))
        ));
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

    public function detailAction()
    {
        if (!$this->_helper->requireAuth()->setAuthParams('socialcommerce_product', null, 'view')->isValid()) {
            return $this->_helper->requireAuth()->forward();
        }
        $viewer = Engine_Api::_()->user()->getViewer();

        if(!Engine_Api::_()->core()->hasSubject())
        {
            return $this->_helper->requireSubject()->forward();
        }

        $this->view->product = $subject = Engine_Api::_()->core()->getSubject();

        if (!$subject) {
            return $this->_helper->requireSubject()->forward();
        }

        //get photos
        $this->view->photos = $photos = Zend_Paginator::factory($subject->getProductPhotoSelect());
        $photos->setCurrentPageNumber(1);
        $photos->setItemCountPerPage(100);

        $this->_helper->content->setEnabled();
        $this->view->product = $subject;

        if (!$viewer->isSelf($subject->getOwner())) {
            $now = new DateTime();
            $subject->view_time = $now->format('y-m-d H:i:s');
            $subject->view_count++;
            $subject->save();
        }

        $can_review = false;
        if (!$subject->isOwner($viewer) && $viewer->getIdentity()) {
            $reviewTable = Engine_Api::_()->getItemTable('socialcommerce_review');
            $reviewSelect = $reviewTable->select()
                ->where('item_id = ?', $subject->getIdentity())
                ->where('user_id = ?', $viewer->getIdentity())
                ->where('type = \'product\'');

            $my_review = $reviewTable->fetchRow($reviewSelect);
            if ($my_review) {
                $this->view->has_review = true;
                $this->view->my_review = $my_review;
                $can_review = false;
            } else {
                $can_review = true;
            }
        }

        $this->view->can_review = $can_review;
    }

    public function emailToFriendsAction()
    {
        if (!$this -> _helper -> requireUser() -> isValid())
            return;

        $this -> view -> listing = $listing = Engine_Api::_() -> core() -> getSubject();

        if (!$listing) {
            return $this->_helper->requireSubject()->forward();
        }

        $this->view->form = $form = new Socialcommerce_Form_EmailToFriends();

        if (!$this -> getRequest() -> isPost()) {
            return;
        }

        if (!$form -> isValid($this -> getRequest() -> getPost())) {
            return;
        }
        $values = $form -> getValues();
        $sentEmails = $listing -> sendEmailToFriends($values['recipients'], @$values['message']);

        $message = Zend_Registry::get('Zend_Translate') -> _("$sentEmails email(s) have been sent.");
        return $this -> _forward('success', 'utility', 'core', array(
            'parentRefresh' => false,
            'smoothboxClose' => true,
            'messages' => $message
        ));
    }
}