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

    public function getAccountsPaginator($params = array())
    {
        $paginator = Zend_Paginator::factory($this->getAccountsSelect($params));
        if (!empty($params['page'])) {
            $paginator->setCurrentPageNumber($params['page']);
        }
        if (!empty($params['limit'])) {
            $paginator->setItemCountPerPage($params['limit']);
        }

        if (empty($params['limit'])) {
            $page = (int)Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.page', 10);
            $paginator->setItemCountPerPage($page);
        }

        return $paginator;
    }

    public function getAccountsSelect($params = array())
    {
        $table = Engine_Api::_()->getDbtable('accounts', 'socialcommerce');
        $rName = $table->info('name');

        $uTable = Engine_Api::_()->getDbtable('users', 'user');
        $uName = $uTable->info('name');

        $select = $table->select()
            ->order(!empty($params['orderby']) ? $params['orderby'] . ' DESC' : $rName . '.creation_date DESC');

        if (!empty($params['user_id']) && is_numeric($params['user_id'])) {
            $select->where($rName . '.user_id = ?', $params['user_id']);
        }

        if (!empty($params['user']) && $params['user'] instanceof User_Model_User) {
            $select->where($rName . '.user_id = ?', $params['user_id']->getIdentity());
        }

        if (!empty($params['user_name'])) {
            $select -> joinLeft($uName, "$uName.user_id = $rName.user_id");
            $select->where($rName . '.displayname LIKE ?', '%'.$params['user_name'].'%');
        }

        return $select;
    }
}