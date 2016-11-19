<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/10/2016
 * Time: 9:17 PM
 */
class Socialcommerce_Model_MailTemplate extends Core_Model_Item_Abstract
{
    protected $_parent_is_owner = true;

    protected $_ordered = false;

    protected $_allowDuplicates = false;

    protected $_searchTriggers = false;

    protected $_type = 'social_template';

    // Complex
    public function getAll()
    {
        $listItemTable = $this->getListItemTable();
        return $listItemTable->fetchAll($this->getSelect());
    }

    public function getSelect()
    {
        return $this->getListItemTable()->select()
            ->where('mailtemplate_id = ?', $this->getIdentity());
    }

    public function getPaginator()
    {
        return Zend_Paginator::factory($this->getSelect());
    }

    // Internal hooks

    protected function _delete()
    {
        foreach ($this->getAll() as $listitem) {
            $listitem->delete();
        }
        parent::_delete();
    }
}