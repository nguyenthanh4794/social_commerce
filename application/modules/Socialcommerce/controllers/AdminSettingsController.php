<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/26/2016
 * Time: 9:59 PM
 */
class Socialcommerce_AdminSettingsController extends Core_Controller_Action_Admin
{
    public function globalAction()
    {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_settings_global');
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $params = array();
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getPost();
        }

        $this->view->form = $form = new Socialcommerce_Form_Admin_Settings_Global(array('params' => $params));

        if (!$this->getRequest()->isPost()){
            return;
        }
        $p_valid = $this->getRequest()->getPost();
        $colorSettings = array(
            'menu_backgroundcolor',
            'menu_hovercolor',
            'menu_textcolor',
            'menu_backgroundbar'
        );
        $formValues  = $form->getValues();
        foreach ($colorSettings as $setting) {
            $p_valid['socialcommerce_'.$setting] = $formValues['socialcommerce_'.$setting];
        }
        if($form->isValid($p_valid)) {
            $p_post = $this->getRequest()->getPost();
            $values = $form->getValues();
            foreach ($colorSettings as $setting) {
                $values['socialcommerce_'.$setting] = $p_post[$setting];
            }
            unset($values['image']);
            foreach ($values as $key => $value) {
                $settings->setSetting($key, $value);
            }
            $form->addNotice('Your changes have been saved.');
        }
    }

    public function levelAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_settings_level');

        if (null !== ($id = $this->_getParam('level_id'))) {
            $level = Engine_Api::_()->getItem('authorization_level', $id);
        }
        else {
            $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
        }

        if(!$level instanceof Authorization_Model_Level) {
            throw new Engine_Exception('missing level');
        }

        $id = $level->level_id;

        // Make form
        $this->view->form = $form = new Socialcommerce_Form_Admin_Settings_Level(array(
            'public' => ( in_array($level->type, array('public')) ),
            'moderator' => ( in_array($level->type, array('admin', 'moderator')) ),
        ));
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $form->level_id->setValue($id);

        $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
        $form->populate($permissionsTable->getAllowed('socialcommerce_listing', $id, array_keys($form->getValues())));

        if ($level->type != 'public') {
            $numberFieldArr = Array('max_listing');
            foreach ($numberFieldArr as $numberField) {
                if ($permissionsTable->getAllowed('socialcommerce_listing', $id, $numberField) == null) {
                    $row = $permissionsTable->fetchRow($permissionsTable->select()
                        ->where('level_id = ?', $id)
                        ->where('type = ?', 'socialcommerce_listing')
                        ->where('name = ?', $numberField));
                    if ($row) {
                        $form->$numberField->setValue($row->value);
                    }
                }
            }
        }

        // Check post
        if(!$this->getRequest()->isPost()) {
            return;
        }

        // Check validitiy
        if(!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        $values = $form->getValues();
        $db = $permissionsTable->getAdapter();
        $db->beginTransaction();
        // Process
        if ($level->type != 'public') {

            $checkArr = array('auth_view', 'auth_comment', 'auth_share', 'auth_photo', 'auth_video', 'auth_discussion');
            foreach ($checkArr as $check) {
                if(empty($values[$check])) {
                    unset($values[$check]);
                    $form->$check->setValue($permissionsTable->getAllowed('socialcommerce_listing', $id, $check));
                }
            }
            try {
                $permissionValues = $values;
                $permissionsTable->setAllowed('socialcommerce_listing', $id, $permissionValues);
                // Commit
                $db->commit();
            }

            catch(Exception $e) {
                $db->rollBack();
                throw $e;
            }
        }
        else {
            try {
                $permissionsTable->setAllowed('socialcommerce_listing', $id, $values);
                $db->commit();
            }

            catch(Exception $e) {
                $db->rollBack();
                throw $e;
            }
        }

        $form->addNotice('Your changes have been saved.');
    }
}