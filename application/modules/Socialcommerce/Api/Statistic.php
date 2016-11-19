<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 4:53 PM
 */
class Socialcommerce_Api_Statistic extends Core_Api_Abstract
{
    static private $_instance;

    static public function getInstance() {
        if(self::$_instance == NULL) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getTotalStalls() {
        $table = Engine_Api::_()->getDbTable('stalls', 'socialcommerce');

        $total = $table->select()
            ->from($table->info('name'), new Zend_Db_Expr('COUNT(*)'))
            ->query()
            ->fetchColumn(0);

        return $total;
    }

    public function getFeaturedStalls() {
        $table = Engine_Api::_()->getDbTable('stalls', 'socialcommerce');

        $total = $table->select()
            ->from($table->info('name'), new Zend_Db_Expr('COUNT(*)'))
            ->where('is_featured = 1 AND status = \'public\'')
            ->query()
            ->fetchColumn(0);

        return $total;
    }

    public function getApprovedStalls() {
        $table = Engine_Api::_()->getDbTable('stalls', 'socialcommerce');

        $total = $table->select()
            ->from($table->info('name'), new Zend_Db_Expr('COUNT(*)'))
            ->where('status = \'public\'')
            ->query()
            ->fetchColumn(0);

        return $total;
    }

    public function getTotalProducts() {
        $table = Engine_Api::_()->getDbTable('products', 'socialcommerce');

        $total = $table->select()
            ->from($table->info('name'), new Zend_Db_Expr('COUNT(*)'))
            ->where('status = \'open\'')
            ->query()
            ->fetchColumn(0);

        return $total;
    }

    public function getFeaturedProducts() {
        $table = Engine_Api::_()->getDbTable('products', 'socialcommerce');

        $total = $table->select()
            ->from($table->info('name'), new Zend_Db_Expr('COUNT(*)'))
            ->where('featured = 1 AND status = \'open\'')
            ->query()
            ->fetchColumn(0);

        return $total;
    }

    public function getApprovedProducts() {
        $table = Engine_Api::_()->getDbTable('products', 'socialcommerce');

        $total = $table->select()
            ->from($table->info('name'), new Zend_Db_Expr('COUNT(*)'))
            ->where('status = \'open\'')
            ->query()
            ->fetchColumn(0);

        return $total;
    }

    public function getTotalSoldProducts() {
        $table = Engine_Api::_()->getDbTable('products', 'socialcommerce');

        $total = $table->select()
            ->from($table->info('name'), new Zend_Db_Expr('SUM(sold_qty)'))
            ->query()
            ->fetchColumn(0);

        return $total;
    }

    public function getStallsPublishFee() {
        return 0;
    }

    public function getStallsFeaturedFee() {
        return 0;
    }

    public function getProductsPublishFee() {
        return 0;
    }

    public function getProductsFeaturedFee() {
        return 0;
    }

    public function getCommission() {
        return 0;
    }

    public function getUsersFollow() {
        return 0;
    }

    public function getUsersFavourite() {
        return 0;
    }

    public function getStallsFollowed() {
        return 0;
    }

    public function getProductsFavourited() {
        return 0;
    }
}