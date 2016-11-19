<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 3:40 PM
 */
class Socialcommerce_AdminFaqsController extends Core_Controller_Action_Admin
{
    public function init()
    {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_main_faqs');
    }

    public function getDbTable()
    {
        return Engine_Api::_()->getDbTable('faqs', 'socialcommerce');
    }

    public function indexAction()
    {
        $faqsTable = $this->getDbTable();
        $select = $faqsTable->select();
        $select->order('ordering asc');

        $paginator = $this->view->paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    }

    public function editAction()
    {
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');

        // Check if selected form is valid
        $faq = Engine_Api::_() -> getItem('socialcommerce_faq', $this -> _getParam('id'));

        if (!$faq) {
            $this -> view -> message = Zend_Registry::get('Zend_Translate') -> _('The faq is not available anymore.');
            return $this -> _forward('success', 'utility', 'core', array(
                'parentRedirect' => Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array('module' => 'socialcommerce', 'controller' => 'faqs'), 'admin_default', true),
                'messages' => Array($this -> view -> message)
            ));
        }

        $this->view->form = $form = new Socialcommerce_Form_Admin_Faqs_Edit;

        $form -> populate($faq->toArray());

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

            $faqsTable = $this->getDbTable();
            // Begin transaction
            $db = $faqsTable->getAdapter();
            $db->beginTransaction();

            try {
                $faq->setFromArray($form->getValues());
                $faq->save();

                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }

            return $this->_forward('success', 'utility', 'core', array(
                'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
                    'module' => 'socialcommerce',
                    'controller' => 'faqs',
                    'action' => 'index',
                ), 'admin_default', true),
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your faq has been successfully updated.'))
            ));
        }

        $this->renderScript('admin-faqs/edit.tpl');
    }

    public function deleteAction()
    {
        // In smoothbox
        $this -> _helper -> layout -> setLayout('admin-simple');
        $id = $this -> _getParam('id');
        $this -> view -> form_id = $id;

        // Check post
        if ($this -> getRequest() -> isPost()) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db -> beginTransaction();

            try {
                $faq = Engine_Api::_() -> getItem('socialcommerce_faq', $id);
                if ($faq)
                    $faq -> delete();

                $db -> commit();
            } catch (Exception $e) {
                $db -> rollBack();
                throw $e;
            }

            return $this -> _forward('success', 'utility', 'core', array(
                'layout' => 'default-simple',
                'parentRefresh' => true,
                'messages' => array(Zend_Registry::get('Zend_Translate') -> _('The faq is deleted successfully.'))
            ));
        }

        // Output
        $this -> _helper -> layout -> setLayout('default-simple');
        $this -> renderScript('admin-faqs/delete.tpl');
    }

    public function createAction()
    {
        $this->view->form = $form = $this->view->form = new Socialcommerce_Form_Admin_Faqs_Create;


        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $faqsTable = $this->getDbTable();
            // Begin transaction
            $db = $faqsTable->getAdapter();
            $db->beginTransaction();

            try {
                $faq = $faqsTable->createRow();
                $faq->setFromArray($form->getValues());
                $faq->save();

                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }

            return $this->_forward('success', 'utility', 'core', array(
                'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
                    'module' => 'socialcommerce',
                    'controller' => 'faqs',
                    'action' => 'index',
                ), 'admin_default', true),
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('New faq has been successfully added.'))
            ));
        }

        $this->renderScript('admin-faqs/create.tpl');
    }

    public function sortAction()
    {
        $faqsTable = $this->getDbTable();
        $select = $faqsTable->select();
        $select->order('ordering asc');

        $faqs = $faqsTable->fetchAll($select);
        $order = explode(',', $this->getRequest()->getParam('order'));
        foreach ($order as $i => $item) {
            $faq_id = substr($item, strrpos($item, '_') + 1);
            foreach ($faqs as $faq) {
                if ($faq->faq_id == $faq_id) {
                    $faq->ordering = $i;
                    $faq->save();
                }
            }
        }
    }
}