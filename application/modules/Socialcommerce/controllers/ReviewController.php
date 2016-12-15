<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/15/2016
 * Time: 12:24 AM
 */
class Socialcommerce_ReviewController extends Core_Controller_Action_Standard
{
    public function getDbTable()
    {
        return Engine_Api::_()->getDbTable('reviews', 'socialcommerce');
    }

    public function createAction()
    {
        // In smoothbox
        $item = Engine_Api::_()->getItem('socialcommerce_product', $this->_getParam('product_id', $this->_getParam('stall_id')));

        if (!$item)
            return $this -> _helper -> requireSubject -> forward();

        // Generate and assign form
        $form = $this -> view -> form = new Socialcommerce_Form_Review_Create();
        $table = $this -> getDbTable();

        if (!$this -> getRequest() -> isPost())
            return;

        if (!$form -> isValid($this -> getRequest() -> getPost()))
            return;

        // We will add the new form
        $values = $form -> getValues();
        $user = Engine_Api::_() -> user() -> getViewer();

        // Begin transaction
        $db = $table -> getAdapter();
        $db -> beginTransaction();

        try {
            $review = $table -> createRow();

            $review -> setFromArray($values);
            $review -> user_id = $user -> getIdentity();
            $review -> item_id = $item -> getIdentity();
            $review -> type = 'product';
            $review -> save();

            $db -> commit();
        } catch (Exception $e) {
            $db -> rollBack();
            throw $e;
        }

        return $this -> _forward('success', 'utility', 'core', array(
            'parentRedirect' => $item->getHref(),
            'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Your review has been successfully added.'))
        ));

    }
}