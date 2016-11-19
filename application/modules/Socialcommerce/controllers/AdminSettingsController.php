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

        $this->view->form = $setting_form = new Socialcommerce_Form_Admin_Settings_Global(array('params' => $params));

        if ($this->getRequest()->isPost() && $setting_form->isValid($this->_getAllParams())) {
            $values = $setting_form->getValues();
            foreach ($values as $key => $value) {
                $settings->setSetting($key, $value);
            }
            $setting_form->addNotice(Zend_Registry::get('Zend_Translate')->_('Your changes have been saved.'));
        }
    }

    public function levelAction()
    {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_settings_level');

        if (null !== ($id = $this->_getParam('level_id'))) {
            $level = Engine_Api::_()->getItem('authorization_level', $id);
        } else {
            $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
        }

        if (!$level instanceof Authorization_Model_Level) {
            throw new Engine_Exception('missing level');
        }

        $id = $level->level_id;

        // Make form
        $this->view->form = $form = new Socialcommerce_Form_Admin_Settings_Level(array(
            'public' => (in_array($level->type, array('public'))),
            'moderator' => (in_array($level->type, array('admin', 'moderator'))),
        ));
        $form->level_id->setValue($id);

        // Populate values
        $formSettingValues = $form->getSettingsValues();
        $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');

        $valuesKeyListings = $permissionsTable->getAllowed('socialcommerce_listing', $id, array_keys($formSettingValues['listing']));
        $valuesListings = array();
        foreach ($valuesKeyListings as $key => $value)
        {
            $valuesListings['listing_'.$key] = $value;
        }

        $valuesKeyStalls = $permissionsTable->getAllowed('socialcommerce_stall', $id, array_keys($formSettingValues['stall']));
        $valuesStalls = array();
        foreach ($valuesKeyStalls as $key => $value)
        {
            $valuesStalls['stall_'.$key] = $value;
        }

        $form->populate(array_merge($valuesStalls, $valuesListings));

        // Check post
        if (!$this->getRequest()->isPost()) {
            return;
        }

        // Check validitiy
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        // Process
        $values = $form->getValues();
        $settingValues = $form->getSettingsValues();
        $db = $permissionsTable->getAdapter();
        $db->beginTransaction();
        // Process
        if ($level->type != 'public') {

            $checkArr = array('auth_view', 'auth_photo', 'auth_video', 'auth_comment');
            foreach ($checkArr as $check) {
                if (empty($values['listing_'.$check])) {
                    unset($values['listing_'.$check]);
                    $form->$check->setValue($permissionsTable->getAllowed('socialcommerce_listing', $id, 'auth_'.$check));
                }

                if (empty($values['stall_'.$check])) {
                    unset($values['stall_'.$check]);
                    $form->$check->setValue($permissionsTable->getAllowed('socialcommerce_stall', $id, 'auth_'.$check));
                }
            }
            try {
                $permissionsTable->setAllowed('socialcommerce_listing', $id, $settingValues['listing']);
                $permissionsTable->setAllowed('socialcommerce_stall', $id, $settingValues['stall']);
                // Commit
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
        } else {
            try {
                $permissionsTable->setAllowed('socialcommerce_listing', $id, $values['listing_view']);
                $permissionsTable->setAllowed('socialcommerce_stall', $id, $values['stall_view']);
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
        }

        $form->addNotice('Your changes have been saved.');
    }
}