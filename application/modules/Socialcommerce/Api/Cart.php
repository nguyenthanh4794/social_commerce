<?php

class Socialcommerce_Api_Cart {

	const SESSION_NAME = 'STORE_CART';

	private $_cartitems  = array();

	static private $_instance;

	private $_modelItems;

	private $_cart;

	private $_order;

	private $_session;

	/**
	 * @return Zend_Session_Namespace
	 */
	public function getSession() {
		if($this -> _session == null) {
			$this -> _session = new Zend_Session_Namespace(self::SESSION_NAME);
		}
		return $this -> _session;
	}

	/**
	 * @return Socialcommerce_Model_Carts
	 */
	public function getCart() {
		
		if($this -> _cart === NULL) {
			$session = $this -> getSession();
			if($session -> cart_id) {
				$cart_id = $session -> cart_id;
			}
			$Carts = new Socialcommerce_Model_DbTable_Carts;
			$cart = NULL;

			if(@$cart_id) {
				$cart = $Carts -> find($cart_id) -> current();
			}

			if(!is_object($cart)) {
				$cart = $Carts -> fetchNew();
				$viewer = Engine_Api::_()->user()->getViewer()->getIdentity();
				if ($viewer != 0) {
					$cart->owner_id = $viewer;
				}
				else {
					if(!empty($session -> guest_id)) {
						$cart->guest_id = $session -> guest_id;
						
					}
					else {
						$select = $Carts->select()->from($Carts, array(new Zend_Db_Expr('max(guest_id) as maxId')));
						$guest_id = $Carts->fetchRow($select)->maxId + 1;
		            	$cart->guest_id = $guest_id;
					}
				}
				$cart -> save();
				$session -> cart_id = $cart -> getId();
				if (@$guest_id) {
					$session->guest_id = $guest_id;
				}
			}
			
			$this -> _cart = $cart;
		}
		return $this -> _cart;
	}

	/**
	 * @return int
	 */
	public function getViewerId() {
		return Engine_Api::_()->user()->getViewer()->getIdentity();
		/*if ($viewer != 0) {
			return $viewer;
		}
		else {
			if($session -> guest_id) {
				return $session -> guest_id;
			}
			else {
				$Carts = new Socialcommerce_Model_DbTable_Carts;
				$select = $Carts->select()->from($Carts, array(new Zend_Db_Expr('max(guest_id) as maxId')));
				$guest_id = $Carts->fetchAll($select)->guest_id + 1;
	           	$session->guest_id = $guest_id;
            	return $guest_id;
			}
		}*/
	}

	/*
	 * @return true|fase
	 */
	public function isEmpty() {
		return false;
	}

	/**
	 * @return array[Socialcommerce_Model_CartITem]
	 */
	public function getCartItems() {
		if($this -> _cartitems == NULL) {
			$model = $this -> getModelItems();
			$select = $model -> select() -> where('cart_id=?', $this -> getCart() -> getIdentity());
			foreach($model -> fetchAll($select) as $item) {
				$this -> _cartitems[] = $item;
			}

		}
		return $this -> _cartitems;
	}

	/**
	 * reset cart item
	 * @return Socialcommerce_Api_Cart
	 */
	public function refresh() {
		$this -> _cartitems = array();
	}

	/**
	 * @param    int   $product_id
	 * @param    int   $product_quantity
	 */
	public function addItem($product_id, $product_qty = 1, $reset = false, $options = null) {
		$item = $this -> _findItemInCart($product_id);
		if(!is_object($item)) {
			$item = $model = $this -> getModelItems() -> fetchNew();
			$item -> item_id = $product_id;
			$item -> item_qty = 0;
			$item -> cart_id = $this -> getCart() -> getId();
			$item -> owner_id = $this -> getCart() -> owner_id;
			$item->save();

			$item -> creation_date = date('Y-m-d H:i:s');
			$this -> _cartitems[] = $item;
		}

		if($reset == true) {
			$item -> item_qty = $product_qty;
		} else {
			$product = Engine_Api::_()->getItem('socialcommerce_product', $product_id);
            $temp = $item->item_qty;
            $temp += $product_qty;
            $max = $product->getCurrentAvailable();
            if ($max != "unlimited") {
                if ($temp >= $max) {
                    $item->item_qty = $max;
                }
                else {
                    $item->item_qty = $temp;
                }
            }
            else {
                $item->item_qty = $temp;
            }
		}
		$item -> save();
		return $this;
	}

	/**
	 * @return Socialcommerce_Model_CartItem|NULL
	 */
	protected function _findItemInCart($product_id) {
		foreach($this->getCartItems() as $item) {
            if($item -> item_id == $product_id) {
                return $item;
            }
		}
		return null;
	}

	/**
	 * @param    int   $product_id
	 */
	public function getItemQty($product_id) {
		$item = $this -> _findItemInCart($product_id);
		if(is_object($item)) {
			return $item -> item_qty;
		}
		return 0;
	}

	/**
	 * @param    int|array   $product_id
	 */
	public function removeCartItem($product_id) {
		$item = $this -> _findItemInCart($product_id);
		if(is_object($item)) {
			$item -> delete();
		}
	}

	public function  removeCarts() {
		$items = $this ->getCartItems();
		foreach ($items as $item) {
			$item -> delete();
		}
	}
	
	/**
	 * count total product of themes.
	 * @return int
	 */
	public function countAllQty() {
		$sum = 0;
		foreach($this->getCartItems() as $item) {
			$sum += $item -> item_qty;
		}
		return $sum;
	}
	/**
	 * @return Socialcommerce_Api_Cart
	 */
	static public function getInstance() {
		if(self::$_instance == NULL) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * @return Socialcommerce_Model_DbTable_CartItems
	 */
	public function getModelItems() {
		if($this -> _modelItems == NULL) {
			$this -> _modelItems = new Socialcommerce_Model_DbTable_CartItems;
		}
		return $this -> _modelItems;
	}

	/**
	 * @param  array 			$params {[product_id]=>[product_quantity]}
	 * @return $order
	 */
	public function makeOrder($cartitems) {
		$tableProducts =  new Socialcommerce_Model_DbTable_Products;
		$order = $this -> getOrder();

		/*
		 * add product to current order as same ways!
		 */
		foreach($cartitems as $key => $product_quantity) {
			$key = explode('_', $key);
			$product_id = $key[0];
			$product = $tableProducts -> find($product_id) -> current();
			$order -> getPlugin() -> addItem($product, $product_quantity['qty'], false);
		}
		$items = $order->getItems();
		foreach ($items as $item) {
			if ($item->pretax_price <= 0) {
				$string = "invalid";
				return $string;
			}
		}
		$order -> saveInsecurity();
		return $order;
	}

	public function getOrder() {
		if($this -> _order == null) {
			$Orders = new Socialcommerce_Model_DbTable_Orders;
			$order = NULL;
			$session = $this -> getSession();

			if($session -> order_id) {
				$order_id = $this -> _session -> order_id;
				$order = $Orders -> find($order_id) -> current();
			}

			if(!is_object($order) || $order->payment_status != 'initial') {
				$order = $Orders -> fetchNew();
				$order -> paytype_id = 'shopping-cart';
				if ($this->getViewerId() != 0) {
					$order->owner_id = $this->getViewerId();
				}
				else {
					if ($session -> guest_id) {
						$order -> guest_id = $session -> guest_id;
					}
					else {
						$order -> guest_id = 0;
					}
				}
				$order->currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('store.currency', 'USD');
				$order -> save();
			}

			$session -> order_id = $order -> getId();

			$this -> _order = $order;
		}
		return $this -> _order;
	}
	
	public function setOwner($viewer){
		$cart = $this->getCart();
		$cart->owner_id =  $viewer->getIdentity();
		$cart->save();
	}

	/**
	 * clean current order from session
	 * clean item from shopping cart.
	 */
	public function flushCurrentOrder(){
		$session = $this->getSession();
		$session->order_id =  null;
		$this->_order = null;
	}

    public function getFinanceAccount($user_id = null, $gateway_id = null)
    {
        $table  = Engine_Api::_()->getDbtable('accounts', 'socialcommerce');
        $select = $table->select();

        if($user_id != null)
        {
            $select->where('user_id = ?',$user_id);
        }

        if($gateway_id != null)
        {
            $select->where('gateway_id = ?',$gateway_id);
        }
        $accounts =   $table->fetchAll($select)->toArray();
        return $accounts[0];
    }

    /**
     * Get Security Code  for transcation
     *
     */
    public function getSecurityCode()
    {
        $sid = 'abcdefghiklmnopqstvxuyz0123456789ABCDEFGHIKLMNOPQSTVXUYZ';
        $max =  strlen($sid) - 1;
        $res = "";
        for($i = 0; $i<16; ++$i){
            $res .=  $sid[mt_rand(0, $max)];
        }
        return $res;
    }
}
