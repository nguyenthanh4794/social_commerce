<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/15/2016
 * Time: 10:38 PM
 */
class Socialcommerce_Model_Review extends Core_Model_Item_Abstract
{
    protected $_parent_type = 'socialcommerce_stall';
    protected $_owner_type = 'user';
    protected $_type = 'socialcommerce_review';
    protected $_searchTriggers = false;

    function isViewable() {
        return $this->authorization()->isAllowed(null, 'view');
    }

    function isEditable() {
        $listing = $this->getParent();
        if ($listing) {
            $viewer = Engine_Api::_() -> user() -> getViewer();
            if ($listing->isOwner($viewer)) {
                return true;
            }
        }
        return $this->authorization()->isAllowed(null, 'edit');
    }

    function isDeletable() {
        $listing = $this->getParent();
        if ($listing) {
            $viewer = Engine_Api::_() -> user() -> getViewer();
            if ($listing->isOwner($viewer)) {
                return true;
            }
        }
        return $this->authorization()->isAllowed(null, 'delete');
    }
}