<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/19/2016
 * Time: 4:51 PM
 */
class Socialcommerce_AdminStatisticsController extends Core_Controller_Action_Admin
{
    public function init()
    {
        $this -> view -> navigation = $navigation = Engine_Api::_() -> getApi('menus', 'core') -> getNavigation('socialcommerce_admin_main', array(), 'socialcommerce_admin_main_statistics');
    }

    public function indexAction(){
        $statistic = Engine_Api::_()->getApi('statistic', 'socialcommerce');
        $this->view->totalStalls = $statistic->getTotalStalls();
        $this->view->featuredStalls = $statistic->getFeaturedStalls();
        $this->view->approvedStalls = $statistic->getApprovedStalls();
        $this->view->usersFollow = $statistic->getUsersFollow();
        $this->view->storesFollowed = $statistic->getStallsFollowed();
        $this->view->totalProducts = $statistic->getTotalProducts();
        $this->view->featuredProducts = $statistic->getFeaturedProducts();
        $this->view->approvedProducts = $statistic->getApprovedProducts();
        $this->view->usersFavourite = $statistic->getUsersFavourite();
        $this->view->productsFavourited = $statistic->getProductsFavourited();
        $this->view->soldProducts = $statistic->getTotalSoldProducts();
        $this->view->storesPublishFee = $storesPubFee = $statistic->getStallsPublishFee();
        $this->view->storesFeaturedFee = $storesFeaFee = $statistic->getStallsFeaturedFee();
        $this->view->storesFee = $storesFee = $storesPubFee + $storesFeaFee;
        $this->view->productsPublishFee = $productsPubFee = $statistic->getProductsPublishFee();
        $this->view->productsFeaturedFee = $productFeaFee = $statistic->getProductsFeaturedFee();
        $this->view->productsFee = $productsFee = $productsPubFee + $productFeaFee;
        $this->view->commission = $commission = $statistic->getCommission();
        $this->view->totalIncome = $storesFee + $productsFee + $commission;
    }
}