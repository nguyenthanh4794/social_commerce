<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/6/2016
 * Time: 3:08 PM
 */
class Socialcommerce_Model_Order extends Core_Model_Item_Abstract
{
    protected $_searchTriggers = false;
    protected $_items;

    /**
     * Identifier getter
     *
     * @return string
     */
    public function getId()
    {
        return $this->order_id;
    }

    public function getState()
    {
        return $this->state;
    }

    /**
     * Total amount getter
     *
     * @return decimal (16,2)
     */
    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    public function getSubAmount()
    {
        return $this->sub_amount;
    }

    /**
     * Tax amount getter
     *
     * @return decimal (16,2)
     */
    public function getTaxAmount()
    {
        return $this->tax_amount;
    }

    /**
     * Currency getter
     *
     * @return string   char(3)
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Shipping amount getter
     *
     * @return decimal (16,2)
     */
    public function getShippingAmount()
    {
        return $this->shipping_amount;
    }

    /**
     * Handling amount getter
     *
     * @return decimal (16,2)
     */
    public function getHandlingAmount()
    {
        return $this->handling_amount;
    }

    /**
     * Discount amount getter
     *
     * @return decimal (16,2)
     */
    public function getDiscountAmount()
    {
        return $this->discount_amount;
    }

    public function getCommissionAmount()
    {
        return $this->commission_amount;
    }

    /**
     * Items getter
     *
     * @return array of Socialcommerce_Payment_Order_Item
     */
    public function getItems()
    {
        // check to get all items;
        if ($this->_items == null) {
            $Items = new Socialcommerce_Model_DbTable_OrderItems;
            $select = $Items->select()->where('order_id=?', $this->order_id);
            foreach ($Items->fetchAll($select) as $item) {
                $this->_items[] = $item;
            }
        }
        return $this->_items;
    }

    /**
     * Get assigned to request item by item identifier
     *
     * @param string $index item identifier
     * @return Socialcommerce_Payment_Order_Item_Interface
     */
    public function getItem($index)
    {
        $items = $this->getItems();
        return @$items[$index];
    }

    public function getItemByProductId($product_id)
    {
        $Items = new Socialcommerce_Model_DbTable_OrderItems;
        $select = $Items->select()->where('order_id=?', $this->order_id)->where('object_id = ?', $product_id);
        return $Items->fetchRow($select);
    }

    public function getItemByProOpt($product_id, $options)
    {
        $Items = new Socialcommerce_Model_DbTable_OrderItems;
        $select = $Items->select()->where('order_id=?', $this->order_id)->where('object_id = ?', $product_id);
        if ($options != null && $options != '') {
            $select->where('options = ?', $options);
        }
        return $Items->fetchRow($select);
    }

    /**
     * Shipping address getter
     *
     * @return Socialcommerce_Payment_Order_Interface_Address | null
     */
    public function getShippingAddress()
    {
        $addressTable = new Socialcommerce_Model_DbTable_ShippingAddresses;
        return $addressTable->find($this->shipping_id)->current();
    }

    /**
     * Order options getter
     *
     * @return Socialcommerce_Payment_Options
     */
    public function getOptions()
    {
        return '';
    }

    public function getPaytype()
    {
        return $this->paytype_id;
    }

    public function getIdentity()
    {
        return $this->order_id;
    }

    public function getPlugin()
    {
        $table = new Socialcommerce_Model_DbTable_Paytypes;
        $paytype = $this->paytype_id;
        $item = $table->find((string)$paytype)->current();
        if (!is_object($item)) {
            throw new Exception("invalid $paytype or $paytype does not exists!");
        }
        $plugin = $item->getPlugin();
        $plugin->setOrder($this);
        return $plugin;
    }

    public function updateOrder()
    {
        $this->save();
    }

    public function addItem($item, $qty, $params)
    {
        return $this->getPlugin()->addItem($item, $qty, $params);
    }

    public function getQty()
    {
        return $this->quantity;
    }

    public function getItemQty($item)
    {

    }

    public function setItemQty($item)
    {

    }

    public function removeItem($item)
    {
        $this->tax_amount -= $item->getTaxAmount() * $item->getQty();
        $this->handling_amount -= $item->getHandlingAmount();
        $this->discount_amount -= $item->getDiscountAmount();
        $this->shipping_amount -= $item->getShippingAmount();
        $this->sub_amount -= $item->getSubAmount();
        $this->quantity -= $item->getQty();
        $this->total_amount -= $item->getTotalAmount();
        parent::save();
        $item->delete();
    }

    public function removeAll()
    {
        $items = $this->getItems();
        foreach ($items as $item) {
            $item->delete();
        }
        $this->tax_amount = 0;
        $this->handling_amount = 0;
        $this->discount_amount = 0;
        $this->shipping_amount = 0;
        $this->sub_amount = 0;
        $this->quantity = 0;
        $this->total_amount = 0;
        parent::save();
    }

    public function saveInsecurity()
    {
        $items = $this->getItems();
        $total_amount = 0;
        $discount_amount = 0;
        $handling_amount = 0;
        $tax_amount = 0;
        $shipping_amount = 0;
        $sub_amount = 0;
        $commission_amount = 0;
        $quantity = 0;
        foreach ($items as $item) {
            $total_amount += $item->getTotalAmount();
            $handling_amount += $item->getHandlingAmount();
            $tax_amount += $item->getTaxAmount() * $item->getQty();
            $shipping_amount += $item->getShippingAmount();
            $discount_amount += $item->getDiscountAmount();
            $commission_amount += $item->getCommissionAmount();
            $sub_amount += $item->getSubAmount();
            $quantity += $item->getQty();
        }
        $this->tax_amount = $tax_amount;
        $this->handling_amount = $handling_amount;
        $this->discount_amount = $discount_amount;
        $this->shipping_amount = $shipping_amount;
        $this->sub_amount = $sub_amount;
        $this->commission_amount = $commission_amount;
        $this->quantity = $quantity;
        $this->total_amount = $total_amount;

        parent::save();
    }


    /**
     * @return true|false
     */
    public function noBilling()
    {
        return $this->getPlugin()->noBilling();
    }

    /**
     * @return true|false
     */
    public function noShipping()
    {
        return $this->getPlugin()->noShipping();
    }

    public function noPackage()
    {
        $Packages = new Socialcommerce_Model_DbTable_ShippingPackages;
        $select = $Packages->select()->where('order_id = ?', $this->order_id);
        $result = $Packages->fetchRow($select);
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getProducts()
    {
        $tblOrderItems = Engine_Api::_()->getDbTable('orderItems', 'socialcommerce');
        $tName = $tblOrderItems->info('name');

        $product_ids = $tblOrderItems->select()
            ->from($tName, 'object_id')
            ->where('order_id=?', $this->order_id)
            ->order('orderitem_id DESC')
            ->query()
            ->fetchAll(Zend_Db::FETCH_COLUMN);

        $products = Engine_Api::_()->getItemMulti('socialcommerce_product', $product_ids);

        $moreInfos = $tblOrderItems->select()
            ->from($tName, array('quantity', 'total_amount'))
            ->where('order_id=?', $this->order_id)
            ->order('orderitem_id DESC')
            ->query()
            ->fetchAll();

        return array($products, $moreInfos);
    }

    public function getTotalAmountByStore($store_id)
    {
        $items = $this->getItems();
        $total = 0;
        foreach ($items as $item) {
            if ($item->store_id == $store_id) {
                $total += $item->total_amount;
            }
        }
        return $total;
    }

    public function updateStatus($status)
    {
        $this->order_status = $status;
        $this->save();
    }
}