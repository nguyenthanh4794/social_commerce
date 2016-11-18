<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 6:28 PM
 */
class Socialcommerce_Plugin_Payment_ShoppingCart extends Socialcommerce_Plugin_Payment_Abstract
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

    public function getSuccessRedirectUrl(){
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $url =  $router->assemble(array('module'=>'socialcommerce','controller'=>'my-cart'),'default',true);
        return $url;
    }

    public function getCancelRedirectUrl(){
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $url =  $router->assemble(array('module'=>'socialcommerce','controller'=>'my-cart'),'default',true);
        return $url;
    }

    public function onSuccess() {
        $order = $this -> getOrder();
        $order -> payment_status = 'completed';
        $order -> save();

        $cart = Socialcommerce_Api_Cart::getInstance();
        $cart->removeCarts();
        $orderitems = $order->getItems();
        $xhtml = "";
        $transactionModel = new Socialcommerce_Model_DbTable_PayTrans;
        $transelect = $transactionModel->select()->where('order_id = ?', $order->order_id);
        $transactionId = $transactionModel->fetchRow($transelect)->transaction_id;
        $modelBilling = new Socialcommerce_Model_DbTable_BillingAddresses;
        $select = $modelBilling->select()->where("order_id = ?", $order->order_id);
        $result = $modelBilling -> fetchRow($select);
        $params = array();
        $result = (array)Zend_Json::decode($result->value);
        $params['stall_orderid'] = $order->order_id;
        $params['buyer_name'] = $result['fullname'];
        $params['buyer_email'] = $result['email'];
        $params['buyer_address'] = $result['street'].' '.$result['city'].' City, '.$result['country'];
        foreach ($orderitems as $item) {

            // Update Product Quantity
            $product = $item->getObject();
            $item->delivery_status = 'shipping';
            $item->payment_status = $order->payment_status;
            $item->save();
            $product->sold_qty += $item->quantity;
            $product->save();

            // Update Stall Product Quantity

            $stall = $product->getStall();
            $stall->sold_products += $item->quantity;
            $stall->save();
            // Send Email to Seller of each products

            $sendTo = Engine_Api::_()->getItem('user', $product->owner_id)->email;
            $params['product_title'] = $product->title;
            $params['stall_title'] = $stall->title;
            $params['stall_link'] = $this->selfURL().$stall->getHref();
            $params['product_link'] = $this->selfURL().$product->getHref();
            $params['product_quantity'] = $item->quantity;
            $params['product_price'] = $item->price;
            $params['product_total'] = $item->total_amount;
            Engine_Api::_()->getApi('mail','socialcommerce')->send($sendTo, 'stall_purchaseseller',$params);

            // Prepare html content to send to Buyer

            $xhtml .= "<div>==========Product Detail==============</div>
						<div><span>Product Name: </span>
							 <span>".$product->title."</span>
						</div>
						<div><span>Product Link: </span>
							<span>".$this->selfURL().$product->getHref()."</span>
						</div>					
						<div><span>Stall Name: </span>
							 <span>".$stall->title."</span>
						</div>
						<div><span>Stall Link: </span>
							<span>".$this->selfURL().$stall->getHref()."</span>
						</div>
						<div><span>Quantity: </span>
							<span>".$item->quantity."</span>
						</div>
						<div><span>Unit Price: </span>
							<span>".$item->price."</span>
						</div>
						<div><span>Total: </span>
							<span>".$item->total_amount."</span>							
						</div>
					";
//            if ($product->product_type == 'downloadable') {
//                $download_link = $product->generateDownloadUrl($order->order_id);
//                $xhtml .= "<div><span>Product Download Link: </span>
//								<span>".$download_link."</span>
//							</div>
//			";
//            }
        }

        // Prepare html content to send to Buyer


        $xhtml .= "<div>==========Transaction Detail==============</div>
				<div><span>Order ID: </span>
				<span>".$order->order_id."</span>
				</div>
				<div><span>Transaction ID: </span>
				<span>".$transactionId."</span>
				</div>
			";


        $email = $result['email'];
        $buyerparams = $order->toArray();
        $buyerparams['buyer_name'] = $result['fullname'];
        $buyerparams['buyer_email'] = $email;
        $buyerparams['buyer_address'] = $result['street'].' '.$result['city'].' City, '.$result['country'];
        $buyerparams['ordercontent'] = $xhtml;

        Engine_Api::_()->getApi('mail','socialcommerce')->send($email, 'stall_purchasebuyer',$buyerparams);
    }

    public function onFailure() {
        $order = $this -> getOrder();
        $order -> payment_status = 'failure';
        $order -> save();
        $cart = Socialcommerce_Api_Cart::getInstance();
        $cart->removeCarts();
        $orderitems = $order->getItems();
        foreach ($orderitems as $item) {
            $item->payment_status = $order->payment_status;
            $item->save();
        }
    }

    public function onPending() {
        $order = $this -> getOrder();
        $order -> payment_status = 'pending';
        $order -> save();
        $orderitems = $order->getItems();
        foreach ($orderitems as $item) {
            $item->payment_status = $order->payment_status;
            $item->save();
        }
    }

    public function onCancel() {
        $order = $this -> getOrder();
        $order -> payment_status = 'cancel';
        $order -> save();
        foreach ($orderitems as $item) {
            $item->payment_status = $order->payment_status;
            $item->save();
        }
    }

    public function noBilling() {
        return false;
    }

    public function noShipping() {
        return false;
    }

    public function addItem($item, $qty, $save_order, $options = null) {
        $order = $this -> getOrder();
        $orderItem = $this -> getByObjectId($item -> getIdentity(),$options);
        // check not exists

        if(!is_object($orderItem)) {
            $item->setQuantity($qty);
            $user = Engine_Api::_()->getItem('user', $item->owner_id);
            $commission = Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('socialcommerce_product', $user, 'product_com');
            $orderItem = $this -> getModelOrderItems() -> fetchNew();

            $orderItem -> name = $item -> getTitle();
            $orderItem -> description = $item -> getTitle();
            $orderItem -> order_id = $this -> getOrder() -> getId();
            $orderItem -> pretax_price = $item -> getPretaxPrice();
            $orderItem -> item_commission_amount = round($item->getPretaxPrice() * $commission/100, 2);
            $orderItem -> currency = $item -> getCurrency();
            $orderItem -> object_id = $item -> getIdentity();
            $orderItem -> object_type = $this -> getOrder() -> getPaytype();
            $orderItem -> quantity = $qty;
            $orderItem -> item_tax_amount = $item->getItemTaxAmount();
        } else {
            $orderItem -> quantity += $qty;
            $item->setQuantity($orderItem -> quantity);
        }

        $orderItem ->stall_id =  $item->stall_id;
        $orderItem -> currency = $item -> getCurrency();
        $orderItem -> commission_amount = round($orderItem->item_commission_amount * $orderItem -> quantity, 2);
        $orderItem -> price = round($item -> getPretaxPrice() * $item -> getTaxPercentage() / 100, 2) + $item -> getPretaxPrice();
        $tax_amount = $item -> getPretaxPrice() * $item -> getTaxPercentage() / 100;
        $orderItem -> tax_amount = round($tax_amount, 2);
        $orderItem -> sub_amount = $orderItem -> quantity * $orderItem -> pretax_price;
        $orderItem -> total_amount = $orderItem -> sub_amount + $orderItem -> tax_amount * $orderItem -> quantity;
        $orderItem -> seller_amount = $orderItem -> total_amount - $orderItem -> commission_amount;

        // persistance this order items
        $orderItem -> save();

        // notify parent order update properties
        if($save_order != false){

            $this -> getOrder() -> saveInsecurity();
        }
    }
}