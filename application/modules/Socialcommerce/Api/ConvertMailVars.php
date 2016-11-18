<?php

class Socialcommerce_Api_ConvertMailVars extends Core_Api_Abstract
{
	
	protected static $_baseUrl;
	
	public static function getBaseUrl(){
		if(self::$_baseUrl == NULL){
			$request =  Zend_Controller_Front::getInstance()->getRequest();
			self::$_baseUrl = sprintf('%s://%s', $request->getScheme(), $request->getHttpHost());
			
		}
		return self::$_baseUrl;
	}
	/**
	 * @param   string $type
	 * @return  string
	 */
	public function selfURL() 
    {
      return self::getBaseUrl();
    }

	public function inflect($type) {
		return sprintf('vars_%s', $type);
	}

	public function vars_default($params, $vars) {
		return $params;
	}

	/**
	 * call from api
	 */
	public function process($params, $vars, $type) {
		$method_name = $this->inflect($type);
		if(method_exists($this, $method_name)) {
			return $this -> {$method_name}($params, $vars);
		}
		return $this -> vars_default($params, $vars);
	}

	/**
	 *
	 */


	public function vars_stall_approvestall($params, $vars) {
		$rparams = array();
		$rparams['stall_title'] = "\"".$params['title']."\"";
		$stall = Engine_Api::_()->getItem('socialcommerce_stall', $params['stall_id']);
		$rparams['stall_link'] = $this->selfURL().$stall->getHref();
		return $rparams;
	}
	
	public function vars_stall_approveproduct($params, $vars) {
		$rparams = array();
		$rparams['product_title'] = "\"".$params['title']."\"";
		$product = Engine_Api::_()->getItem('socialcommerce_product', $params['product_id']);
		$stall = Engine_Api::_()->getItem('socialcommerce_stall', $params['stall_id']);
		$rparams['product_link'] = $this->selfURL().$product->getHref();
		$rparams['stall_title'] = $stall->title;
		$rparams['stall_link'] = $this->selfURL().$stall->getHref();
		return $rparams;
	}
	
	public function vars_stall_purchasebuyer($params, $vars) {
		$rparams = array();
		$rparams['ordercontent'] = $params['ordercontent'];
		$rparams['buyer_name'] = $params['buyer_name'];
		$rparams['buyer_email'] = $params['buyer_email'];
		$rparams['buyer_address'] = $params['buyer_address'];
		return $rparams;
	}
	public function vars_stall_purchaseseller($params, $vars) {
		$rparams = array();
		$rparams['stall_title'] = $params['stall_title'];
		$rparams['stall_link'] = $params['stall_link'];
		$rparams['product_title'] = $params['product_title'];
		$rparams['product_link'] = $params['product_link'];
		$rparams['stall_orderid'] = $params['stall_orderid'];
		$rparams['product_quantity'] = $params['product_quantity'];
		$rparams['product_price'] = $params['product_price'];
		$rparams['product_total'] = $params['product_total'];
		$rparams['buyer_name'] = $params['buyer_name'];
		$rparams['buyer_email'] = $params['buyer_email'];
		$rparams['buyer_address'] = $params['buyer_address'];
		return $rparams;
	}	
	public function vars_stall_requestaccept($params, $vars) {
		$rparams = array();
		$stall = Engine_Api::_()->getItem('socialcommerce_stall', $params['stall_id']);
		$rparams['stall_title'] = $stall->title;
		$rparams['stall_link'] = $this->selfURL().$stall->getHref();
		$rparams['request_amount'] = $params['request_amount'];
		return $rparams;
	}
	public function vars_stall_requestdeny($params, $vars) {
		$rparams = array();
		$stall = Engine_Api::_()->getItem('socialcommerce_stall', $params['stall_id']);
		$rparams['stall_title'] = $stall->title;
		$rparams['stall_link'] = $this->selfURL().$stall->getHref();
		$rparams['request_amount'] = $params['request_amount'];
		return $rparams;
	}
	public function vars_stall_productdelete($params, $vars) {
		$rparams = array();
		$stall = Engine_Api::_()->getItem('socialcommerce_stall', $params['stall_id']);
		$rparams['stall_title'] = $stall->title;
		$rparams['stall_link'] = $this->selfURL().$stall->getHref();
		$rparams['product_title'] = $params['title'];
		return $rparams;
	}
	public function vars_stall_productdelbuyers($params, $vars) {
		$rparams = array();
		$stall = Engine_Api::_()->getItem('socialcommerce_stall', $params['owner_id']);
		$rparams['stall_title'] = $stall->title;
		$rparams['stall_link'] = $this->selfURL().$stall->getHref();
		$rparams['product_title'] = $params['title'];
		return $rparams;
	}
	public function vars_stall_productdelfav($params, $vars) {
		$rparams = array();
		$rparams['stall_title'] = $params['stall_title'];
		$rparams['stall_link'] = $this->selfURL().$params['stall_link'];
		$rparams['product_title'] = $params['product_title'];
		return $rparams;
	}
	public function vars_stall_follownotice($params, $vars) {
		$rparams = array();
		$rparams['stall_title'] = $params['stall_title'];
		$rparams['stall_link'] = $this->selfURL().$params['stall_link'];
		$rparams['product_title'] = $params['product_title'];
		$rparams['product_link'] = $this->selfURL().$params['product_link'];
		return $rparams;
	}
	public function vars_stall_refundbuyer($params, $vars) {
		$rparams = array();
		$rparams['stall_title'] = $params['stall_title'];
		$rparams['stall_link'] = $this->selfURL().$params['stall_link'];
		$rparams['product_title'] = $params['product_title'];
		$rparams['product_link'] = $this->selfURL().$params['product_link'];
		$rparams['stall_orderid'] = $params['stall_orderid'];
		return $rparams;
	}
	public function vars_stall_refundseller($params, $vars) {
		$rparams = array();
		$rparams['stall_title'] = $params['stall_title'];
		$rparams['stall_link'] = $this->selfURL().$params['stall_link'];
		$rparams['product_title'] = $params['product_title'];
		$rparams['product_link'] = $this->selfURL().$params['product_link'];
		$rparams['stall_orderid'] = $params['stall_orderid'];
		
		$rparams['buyer_name'] = $params['buyer_name'];
		$rparams['buyer_email'] = $params['buyer_email'];
		$rparams['buyer_address'] = $params['buyer_address'];
		return $rparams;
	}
}


