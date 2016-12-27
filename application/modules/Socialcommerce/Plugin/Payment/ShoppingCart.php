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

    public static function getBaseUrl()
    {
        if (self::$_baseUrl == NULL) {
            $request = Zend_Controller_Front::getInstance()->getRequest();
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

    public function getSuccessRedirectUrl()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $url = $router->assemble(array('module' => 'socialcommerce', 'controller' => 'my-cart'), 'default', true);
        return $url;
    }

    public function getCancelRedirectUrl()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $url = $router->assemble(array('module' => 'socialcommerce', 'controller' => 'my-cart'), 'default', true);
        return $url;
    }

    public function onSuccess()
    {
        $order = $this->getOrder();
        $order->payment_status = 'completed';
        $order->save();

        $cart = Socialcommerce_Api_Cart::getInstance();
        $cart->removeCarts();
        $orderitems = $order->getItems();
        $xhtml = "";
        $transactionModel = new Socialcommerce_Model_DbTable_PayTrans;
        $transelect = $transactionModel->select()->where('order_id = ?', $order->order_id);
        $transactionId = $transactionModel->fetchRow($transelect)->transaction_id;
        $modelShipping = new Socialcommerce_Model_DbTable_ShippingAddresses;
        $select = $modelShipping->select()->where("order_id = ?", $order->order_id);
        $result = $modelShipping->fetchRow($select);
        $params = array();
        $result = (array)Zend_Json::decode($result->value);
        $params['stall_orderid'] = $order->order_id;
        $params['buyer_name'] = $result['fullname'];
        $params['buyer_email'] = $result['email'];
        $params['buyer_address'] = $result['street'] . ' ' . $result['city'] . ' City, ' . $result['country'];
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

            $url = $this->selfURL();
            if (!$url) $url = 'http://35.161.60.158';

            $sendTo = Engine_Api::_()->getItem('user', $product->owner_id)->email;
            $params['product_title'] = $product->title;
            $params['stall_title'] = $stall->title;
            $params['stall_link'] = $url . $stall->getHref();
            $params['product_link'] = $url . $product->getHref();
            $params['product_quantity'] = $item->quantity;
            $params['product_price'] = $item->price;
            $params['product_total'] = $item->total_amount;
            Engine_Api::_()->getApi('mail', 'socialcommerce')->send($sendTo, 'stall_purchaseseller', $params);

            // Prepare html content to send to Buyer

            $xhtml .= "<div>==========Product Detail==============</div>
						<div><span>Product Name: </span>
							 <span>" . $product->title . "</span>
						</div>
						<div><span>Product Link: </span>
							<span>" . $this->selfURL() . $product->getHref() . "</span>
						</div>					
						<div><span>Stall Name: </span>
							 <span>" . $stall->title . "</span>
						</div>
						<div><span>Stall Link: </span>
							<span>" . $this->selfURL() . $stall->getHref() . "</span>
						</div>
						<div><span>Quantity: </span>
							<span>" . $item->quantity . "</span>
						</div>
						<div><span>Unit Price: </span>
							<span>" . $item->price . "</span>
						</div>
						<div><span>Total: </span>
							<span>" . $item->total_amount . "</span>							
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
				<span>" . $order->order_id . "</span>
				</div>
				<div><span>Transaction ID: </span>
				<span>" . $transactionId . "</span>
				</div>
			";


        $email = $result['email'];
        $buyerparams = $order->toArray();
        $buyerparams['buyer_name'] = $result['fullname'];
        $buyerparams['buyer_email'] = $email;
        $buyerparams['buyer_address'] = $result['street'] . ' ' . $result['city'] . ' City, ' . $result['country'];
        $buyerparams['ordercontent'] = $xhtml;
        $buyerparams['deal_dodcontent'] = $this->getDodContent($orderitems);

        Engine_Api::_()->getApi('mail', 'socialcommerce')->send($email, 'stall_purchasebuyer', $buyerparams);
    }

    public function getDodContent($products){
        $translate = Zend_Registry::get('Zend_Translate');
        $this->products =  $products;
        $this->contacts =  array();
        $this->uri = $this->getFullUri();
        $this->site_name = Engine_Api::_()->getApi('settings', 'core')->getSetting('core_general_site_title', $translate->translate('_SITE_TITLE'));
        $filename = APPLICATION_PATH . '/application/modules/Socialcommerce/views/scripts/mail/dod_content.tpl';
        ob_start();
        include $filename;
        $result = ob_get_clean();
        return $result;
    }

    public function getFullUri() {
        $host = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off") ? 'https' : 'http';
        $port = (isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80);
        $uri = $proto . '://' . $host;
        if ((('http' == $proto) && (80 != $port)) || (('https' == $proto) && (443 != $port)))
        {
            $uri .= ':' . $port;
        }
        return $uri;
    }

    public function onFailure()
    {
        $order = $this->getOrder();
        $order->payment_status = 'failure';
        $order->save();
        $cart = Socialcommerce_Api_Cart::getInstance();
        $cart->removeCarts();
        $orderitems = $order->getItems();
        foreach ($orderitems as $item) {
            $item->payment_status = $order->payment_status;
            $item->save();
        }
    }

    public function onPending()
    {
        $order = $this->getOrder();
        $order->payment_status = 'pending';
        $order->save();
        $orderitems = $order->getItems();
        foreach ($orderitems as $item) {
            $item->payment_status = $order->payment_status;
            $item->save();
        }
    }

    public function onCancel()
    {
        $order = $this->getOrder();
        $order->payment_status = 'cancel';
        $order->save();
        foreach ($orderitems as $item) {
            $item->payment_status = $order->payment_status;
            $item->save();
        }
    }

    public function noBilling()
    {
        return false;
    }

    public function noShipping()
    {
        return false;
    }

    public function addItem($item, $qty = 1, $save_order)
    {
        $order = $this->getOrder();
        $orderItem = $this->getByObjectId($item->getIdentity());
        // check not exists

        if (!is_object($orderItem)) {
            $item->setQuantity($qty);
            $user = Engine_Api::_()->getItem('user', $item->owner_id);
            $commission = Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('socialcommerce_product', $user, 'commission', 5);
            if (!$commission)
                $commission = 5;

            $orderItem = $this->getModelOrderItems()->fetchNew();

            $orderItem->name = $item->getTitle();
            $orderItem->description = $item->getTitle();
            $orderItem->order_id = $this->getOrder()->getId();
            $orderItem->pretax_price = $item->getPretaxPrice();
            $orderItem->item_commission_amount = round($item->getPretaxPrice() * $commission / 100, 2);
            $orderItem->currency = $item->getCurrency();
            $orderItem->object_id = $item->getIdentity();
            $orderItem->object_type = $this->getOrder()->getPaytype();
            $orderItem->quantity = $qty;
            $orderItem->item_tax_amount = $item->getItemTaxAmount();
        } else {
            $orderItem->quantity += $qty;
            $item->setQuantity($orderItem->quantity);
        }

        $orderItem->stall_id = $item->stall_id;
        $orderItem->currency = $item->getCurrency();
        $orderItem->commission_amount = round($orderItem->item_commission_amount * $orderItem->quantity, 2);
        $orderItem->price = round($item->getPretaxPrice() * $item->getTaxPercentage() / 100, 2) + $item->getPretaxPrice();
        $tax_amount = $item->getPretaxPrice() * $item->getTaxPercentage() / 100;
        $orderItem->tax_amount = round($tax_amount, 2);
        $orderItem->sub_amount = $orderItem->quantity * $orderItem->pretax_price;
        $orderItem->total_amount = $orderItem->sub_amount + $orderItem->tax_amount * $orderItem->quantity;
        $orderItem->seller_amount = $orderItem->total_amount - $orderItem->commission_amount;
        $orderItem->owner_id = $item->owner_id;

        // persistance this order items
        $orderItem->save();

        // notify parent order update properties
        if ($save_order != false) {

            $this->getOrder()->saveInsecurity();
        }
    }
}