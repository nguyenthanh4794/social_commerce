<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 11:53 PM
 */
class Socialcommerce_Api_Account extends Core_Api_Abstract
{
    public function getCurrentInfo($user_id)
    {
        $table = Engine_Api::_()->getDbtable('accounts', 'socialcommerce');
        $select = $table->select()->setIntegrityCheck(false)
            ->from($table->info('name').' as account', 'account.*')
            ->joinRight('engine4_users', 'engine4_users.user_id =  account.user_id', 'engine4_users.*')
            ->where('engine4_users.user_id = ?', $user_id);
        $result = $table->fetchAll($select)->toArray();
        return @$result[0];
    }

    public function getAccountByUserId($user_id)
    {
        $table = Engine_Api::_()->getDbtable('accounts', 'socialcommerce');

        $select = $table->select()->setIntegrityCheck(false)
            ->from($table->info('name').' as account', 'account.*')
            ->where('account.user_id = ?', $user_id);

        return $table->fetchRow($select);
    }

    public function getCurrentAccount($user_id)
    {
        $table = Engine_Api::_()->getDbtable('accounts', 'socialcommerce');
        $select = $table->select()->setIntegrityCheck(false)
            ->from($table->info('name').' as account', 'account.*')
            ->where('account.user_id = ?', $user_id);
        $result = $table->fetchAll($select)->toArray();
        return @$result[0];
    }

    public function getHistorySellerSelect($params)
    {
        $tt_table = Engine_Api::_()->getDbTable('transactionTrackings', 'socialcommerce');
        $tt_name = $tt_table->info('name');
        $a_table = Engine_Api::_()->getDbTable('deals', 'socialcommerce');
        $a_name = $a_table->info('name');
        $select = $tt_table->select()->setIntegrityCheck(false)
            ->from($tt_table, array('sum(number) as total', "$tt_name.transactiontracking_id"))
            ->joinLeft($a_name, "$a_name.deal_id = $tt_name.item_id", array("$a_name.*"))
            ->where("$a_name.user_id = ? ", $params["user_id"])->where("$tt_name.params = 'buy'")
            ->group("$tt_name.item_id");
        return $select;
    }

    public function getHistorySeller($params)
    {
        $sellerPaginator = Zend_Paginator::factory($this->getHistorySellerSelect($params));
        if (!empty($params['page'])) {
            $sellerPaginator->setCurrentPageNumber($params['page']);
        }
        if (!empty($params['limit'])) {
            $sellerPaginator->setItemCountPerPage($params['limit']);
        }
        return $sellerPaginator;
    }

    public function updateinfo($avals = array())
    {
        $user = Engine_Api::_()->user()->getViewer();
        $user->displayname = $avals['displayname'];
        return $user->save();
    }

    public function updateusername_account($account_id, $account_username)
    {
        $table = Engine_Api::_()->getDbtable('accounts', 'socialcommerce');
        $data = array(
            'account_username' => $account_username
        );
        $where = $table->getAdapter()->quoteInto('account_id = ?', $account_id);
        return $table->update($data, $where);
    }

    public function updatecurrency_account($account_id, $currency)
    {
        $table = Engine_Api::_()->getDbtable('accounts', 'socialcommerce');
        $data = array(
            'currency' => $currency
        );
        $where = $table->getAdapter()->quoteInto('account_id = ?', $account_id);
        return $table->update($data, $where);
    }

    public function updateAmount($account_id, $amounts, $type)
    {
        if (!$account_id) {
            return;
        }
        $table = Engine_Api::_()->getDbtable('accounts', 'socialcommerce');
        $select = $table->select()->from($table->info('name'));
        $select->where('account_id = ?', $account_id);
        $result = $table->fetchRow($select);

        $totalamount = $result->total_amount;
        if ($type == 1)
            $data = array(
                'total_amount' => $totalamount + $amounts
            );
        else
            $data = array(
                'total_amount' => $totalamount - $amounts
            );
        $where = $table->getAdapter()->quoteInto('account_id = ?', $account_id);
        return $table->update($data, $where);
    }

    public function insertAccount($results = array())
    {
        $account = Engine_Api::_()->getDbtable('accounts', 'socialcommerce')->createRow();
        $account->account_username = $results['account_username'];
        $account->currency = $results['currency'];
        $account->total_amount = 0;
        $account->account_status = 1;
        $account->payment_type = 2;
        $account->user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $account->save();
        return 1;
    }

    public function addAccount($user_id)
    {
        $account = Engine_Api::_()->getDbtable('accounts', 'socialcommerce')->createRow();
        $account->account_username = '';
        $account->total_amount = 0;
        $account->account_status = 1;
        $account->payment_type = 2;
        $account->user_id = $user_id;
        $account->save();
        return 1;
    }

    public function getAmountSeller($user_id)
    {
        $lst_table = Engine_Api::_()->getDbTable('buyDeals', 'socialcommerce');
        $lst_name = $lst_table->info('name');
        $ab_table = Engine_Api::_()->getDbTable('deals', 'socialcommerce');
        $ab_name = $ab_table->info('name');
        $s_table = Engine_Api::_()->getDbTable('categories', 'socialcommerce');
        $s_name = $s_table->info('name');
        $select = $lst_table->select()
            ->from("$lst_name as lst", array('count(*) as count', 'lst.item_id'))
            ->joinLeft($ab_name, "$ab_name.deal_id = lst.item_id", '')
            ->joinLeft($s_name, "$s_name.category_id = $ab_name.category_id", '')
            ->where("$ab_name.user_id= ?", $user_id)
            ->orWhere(' 0 < ((SELECT count(*) from  engine4_socialcommerce_deals as deal where deal.user_id=' . $user_id . ' and deal.deal_id = lst.item_id))')
            ->group('lst.item_id');
        $result = $lst_table->fetchAll($select)->toArray();
        return count($result);
    }

    public function insertRequest($values = array())
    {
        $db = Engine_Db_Table::getDefaultAdapter();

        try {
            $request = Engine_Api::_()->getDbtable('requests', 'socialcommerce')->createRow();

            $commission = 5;
            //$commission= Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('groupbuy_deal', $viewer, 'commission');
            $values['commission'] = $commission;
            $values['commission_fee'] = round($commission / 100 * $request->request_amount, 2);
            $values['send_amount'] = $values['request_amount'] - $values['commission_fee'];

            $request->setFromArray($values);

            $request->save();

            $db->commit();

            return $request->getIdentity();
        }
        catch (Exception $e)
        {
            $db->rollBack();
            throw $e;
        }
    }

    public function getTotalRequest($request_user_id, $type = 'payment')
    {
        $table = Engine_Api::_()->getDbtable('requests', 'socialcommerce');
        $select = $table->select()->setIntegrityCheck(false)
            ->from($table->info('name'), array('sum(request_amount) as totalrequest', 'owner_id'))
            ->where('owner_id = ?', $request_user_id)
            ->where('request_type = ?', $type)
            ->group('owner_id');
        $result = $table->fetchAll($select)->toArray();
        return @$result[0]['totalrequest'];
    }

    public function getTotalSold($user_id)
    {
        $ttTable = Engine_Api::_()->getDbtable('transactionTrackings', 'socialcommerce');
        $ttTableName = $ttTable->info('name');
        $select = $ttTable->select();
        $select->from($ttTableName, array("$ttTableName.*"))
            ->setIntegrityCheck(false)
            ->where("$ttTableName.user_seller = ?", $user_id)
            ->group("$ttTableName.transactiontracking_id");
        $result = $ttTable->fetchAll($select);
        $total_sold = 0;
        foreach ($result as $sold)
            $total_sold += $sold->amount;
        return $total_sold;
    }

    public function getTotalCommission($user_id, $account_id)
    {
        $table = Engine_Api::_()->getDbtable('requests', 'socialcommerce');
        $select = $table->select()->from($table->info('name'), "SUM(commission_fee) as total_commission")->where("owner_id = ?", $user_id)->where('tc_account_id = ?', $account_id);
        $result = $table->fetchAll($select)->toArray();
        return @$result[0]['total_commission'];
    }

    public function getWaitingAmount($user_id)
    {
        $table = Engine_Api::_()->getDbtable('requests', 'socialcommerce');
        $select = $table->select()->from($table->info('name'), "SUM(request_amount) as waiting_amount")->where("owner_id = ?", $user_id)->where("request_status = ?", 'waiting');
        $result = $table->fetchAll($select)->toArray();
        return @$result[0]['waiting_amount'];
    }

    public function getReceivedAmount($user_id)
    {
        $table = Engine_Api::_()->getDbtable('requests', 'socialcommerce');
        $select = $table->select()->from($table->info('name'), "SUM(request_amount) as received_amount")->where("owner_id = ?", $user_id)->where("request_status = ?", 'completed');
        $result = $table->fetchAll($select)->toArray();
        return @$result[0]['received_amount'];
    }


    public function getFinanceAccountRequestsByRequestId($request_id)
    {
        $reqTable = Engine_Api::_()->getDbtable('requests', 'socialcommerce');
        $reqName = $reqTable->info('name');

        $accTable = Engine_Api::_()->getDbtable('accounts', 'socialcommerce');
        $accName = $accTable->info('name');

        $select = $accTable->select()->setIntegrityCheck(false)
            ->from($accName . " as acc", 'acc.*')
            ->joinLeft("engine4_users", "engine4_users.user_id = acc.user_id", 'engine4_users.username')
            ->joinLeft($reqName, $reqName . '.tc_account_id = acc.account_id', "$reqName.*")
            ->where($reqName.'.request_id = ?', $request_id)
            ->limit(1);

        $items = $reqTable->fetchAll($select)->toArray();

        return $items[0];
    }
}