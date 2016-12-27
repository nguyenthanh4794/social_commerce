<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/27/2016
 * Time: 10:02 PM
 */
class Socialcommerce_ShippingAddressController extends Core_Controller_Action_Standard
{
    public function createAction()
    {
        if (!$this->_helper->requireUser()->isValid()) return;
        $this->view->form = $form = new Socialcommerce_Form_Payment_Shipping();

        $bIsEdit = false;

        if ($this->_getParam('shippinginformation_id', 0) > 0) {
            $bIsEdit = $this->_getParam('shippinginformation_id');
            $shippingInfo = Engine_Api::_()->getDbTable('shippingAddresses', 'socialcommerce')->find($bIsEdit)->current();

            $form->populate((array) json_decode($shippingInfo->value));
        }

        if ($this->getRequest()->isPost() && $this->view->form->isValid($this->getRequest()->getPost())) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                if ($bIsEdit)
                {
                    $msg = 'Your shipping address has been successfully updated.';
                    $shippingInfo->value = json_encode($form->getValues());
                    $shippingInfo->save();
                }
                else{
                    $msg = 'New shipping address has been successfully added.';
                    $form->saveValues();
                }
                $db->commit();

                return $this -> _forward('success', 'utility', 'core', array(
                    'parentRefresh' => true,
                    'messages' => array(Zend_Registry::get('Zend_Translate') -> _($msg))
                ));
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        }

        $this -> renderScript('shipping/form.tpl');
    }

    public function deleteAction()
    {
        $id = $this -> _getParam('shippinginformation_id');
        $this -> view -> shippinginformation_id = $id;

        // Check post
        if ($this -> getRequest() -> isPost()) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db -> beginTransaction();

            try {
                $shippingInfo = Engine_Api::_()->getDbTable('shippingAddresses', 'socialcommerce')->find($id)->current();
                if ($shippingInfo)
                    $shippingInfo -> delete();

                $db -> commit();
            } catch (Exception $e) {
                $db -> rollBack();
                throw $e;
            }

            return $this -> _forward('success', 'utility', 'core', array(
                'layout' => 'default-simple',
                'parentRefresh' => true,
                'messages' => array(Zend_Registry::get('Zend_Translate') -> _('The shipping address is deleted successfully.'))
            ));
        }

        // Output
        $this -> _helper -> layout -> setLayout('default-simple');
        $this -> renderScript('shipping/delete.tpl');
    }
}