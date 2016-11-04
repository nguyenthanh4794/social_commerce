<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/30/2016
 * Time: 3:51 PM
 */
class Socialcommerce_Model_DbTable_Accounts extends Engine_Db_Table
{
    protected $_rowClass = 'Socialcommerce_Model_Account';

    public function getAccount()
    {
        $viewer = Engine_Api::_()->user()->getViewer();
        $select = $this->select()->where('user_id = ? ', $viewer->getIdentity());
        return $this->fetchRow($select);
    }
}