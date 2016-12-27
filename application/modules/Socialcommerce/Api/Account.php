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

    public function getAmountSeller($result)
    {
        $total_amount = 0;
        foreach ($result as $sold)
            $total_amount += $sold->seller_amount;
        return $total_amount;
    }

    public function insertRequest($values = array())
    {
        $db = Engine_Db_Table::getDefaultAdapter();

        try {
            $request = Engine_Api::_()->getDbtable('requests', 'socialcommerce')->createRow();
            $user = Engine_Api::_()->user()->getViewer();
            $commission = Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('socialcommerce_product', $user, 'commission', 5);

            if (!$commission)
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

    public function getTotalSold($result)
    {
        $total_sold = 0;
        foreach ($result as $sold)
            $total_sold += $sold->quantity;
        return $total_sold;
    }

    public function getTotalCommission($result)
    {
        $total_commission = 0;
        foreach ($result as $sold)
            $total_commission += $sold->item_commission_amount;
        return $total_commission;
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