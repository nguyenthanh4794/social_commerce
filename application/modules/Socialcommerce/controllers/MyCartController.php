<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 12:32 PM
 */
class Socialcommerce_MyCartController extends Core_Controller_Action_Standard
{
    public function init() {
        Zend_Registry::set('active_menu', 'socialcommerce_main_bags');
    }

    public function indexAction() {
        if (!$this -> _helper -> requireUser() -> isValid()) {
            return;
        }

        $cart = Socialcommerce_Api_Cart::getInstance();
        $count = $cart -> countAllQty();
        if ($count < 1) {
            return $this -> _forward('empty-cart');
        }

        try {
            $params = $this -> _getAllParams();
            // View Submit to Check Out
            if (isset($params['checkout_submit'])) {
                if (!$this -> _helper -> requireUser() -> isValid()) {
                    return;
                }
                if ($params['total'] == 0) {
                    return;
                }
                $viewer = Engine_Api::_() -> user() -> getViewer();

                $order = $this -> _checkout();

                if (!is_object($order) && $order == 'invalid') {
                    return $this -> _forward('success', 'utility', 'core', array(
                        'messages' => array($this -> view -> translate('Please remove invalid products before continue!'))));

                }
                $this -> _helper -> redirector -> gotoRoute(array('controller' => 'payment', 'action' => 'process', 'id' => $order -> getId()), 'socialcommerce_extended');
            }

            // View Submit to Update Cart (change quantity)
            if (isset($params['updatecart_submit'])) {
                $this -> _updateCart();
            }

        } catch(Exception $e) {
            throw $e;
        }
        // view cart and some thing else.
    }
    public function ajaxAddProductAction() {
        $viewer = Engine_Api::_() -> user() -> getViewer();
        $allowPurchase = Engine_Api::_() -> authorization() -> getAdapter('levels') -> getAllowed('social_product', $viewer, 'product_buy');

        $cart = Socialcommerce_Api_Cart::getInstance();
        $product_id = $this -> _getParam('product_id');
        $product = Engine_Api::_() -> getItem('socialcommerce_product', $product_id);
        $product_quantity = '1';
        $cart -> addItem($product_id, $product_quantity);
        $count = $cart -> countAllQty();

        if ($count == 1) {
            $msg = Zend_Registry::get('Zend_Translate') -> _($count . ' Item in Cart');
        } elseif ($count > 1) {
            $msg = Zend_Registry::get('Zend_Translate') -> _($count . ' Items in Cart');
        }
        $result = array(
            'count' => $count,
            'error' => 0,
            'ms' => $msg,
        );
        echo json_encode($result);
        exit();
    }

    protected function _updateCart() {
        $params = $this -> _getParam('cartitem_qty');
        $cart = Socialcommerce_Api_Cart::getInstance();

        foreach ($params as $key => $product_quantity) {
            $key = explode('_', $key);
            $product_id = $key[0];
            $product = Engine_Api::_() -> getItem('socialcommerce_product', $product_id);
            $current_max = $product -> getCurrentAvailable();
            $min = $product -> min_qty_purchase;
            if ($current_max == 'unlimited') {
                if ($product_quantity['qty'] < $min) {
                    $product_quantity['qty'] = $min;
                }
            } elseif ($min <= $current_max && $current_max != 0) {
                if ($product_quantity['qty'] < $min) {
                    $product_quantity['qty'] = $min;
                } elseif ($product_quantity['qty'] > $current_max) {
                    $product_quantity['qty'] = $current_max;
                }
            }
            $cart -> addItem($product_id, $product_quantity['qty'], true, $product_quantity['options']);

        }
        $cart -> refresh();
        return;
    }

    protected function _checkout() {
        $cartitem_check = $this -> _getParam('cartitem_check');
        $cartitem_qty = $this -> _getParam('cartitem_qty');
        $cartitems = array();

        foreach ($cartitem_check as $item_id => $item_amount) {
            $value = (int)$cartitem_qty[$item_id]['qty'];
            if ($value < 1) {
                throw new Exception("Invalid Quantity");
            }
            $cartitems[$item_id]['qty'] = $value;
        }
        return Socialcommerce_Api_Cart::getInstance() -> makeOrder($cartitems);
    }

    public function removeItemAction() {
        $id = $this -> _getParam('cartitem-id');
        $cart = Socialcommerce_Api_Cart::getInstance() -> removeCartItem($id);
        $this -> _forward('success', 'utility', 'core', array('smoothboxClose' => true, 'parentRedirect' => $this -> getFrontController() -> getRouter() -> assemble(array('module' => 'socialcommerce', 'controller' => 'my-cart', 'action' => 'index'), 'default', true), 'format' => 'smoothbox', 'messages' => array(Zend_Registry::get('Zend_Translate') -> _('Remove item successfully.'))));
    }

    public function emptyCartAction() {

    }
}