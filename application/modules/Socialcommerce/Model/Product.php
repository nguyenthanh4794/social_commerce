<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/23/2016
 * Time: 9:59 AM
 */
class Socialcommerce_Model_Product extends Core_Model_Item_Abstract
{
    protected $_qty = 0;
    protected $_type = 'socialcommerce_product';

    public function isNew()
    {
        $now = new DateTime();
        $creation_date = new DateTime($this->creation_date);
        $new_days = Engine_Api::_()->getApi('settings', 'core')->getSetting('products_new_days', 1);
        $diff = date_diff($creation_date, $now);
        $measure = ($diff->format('%a'));
        if ($measure <= $new_days) return true;
        return false;
    }

    public function getHref($params = array())
    {
        $slug = $this->getSlug();
        $params = array_merge(array(
            'route' => 'socialcommerce_product_profile',
            'product_id' => $this->getIdentity(),
            'slug' => $slug,
        ),
            $params);
        $route = $params['route'];
        unset($params['route']);
        return Zend_Controller_Front::getInstance()->getRouter()
            ->assemble($params, $route, true);
    }

    public function getProductPhotoSelect()
    {
        $table = Engine_Api::_()->getItemTable('album_photo');
        return $table->select()->where('album_id = 0 AND owner_type = \'socialcommerce_product\' AND item_id = ?', $this->getIdentity());
    }

    public function getRating()
    {
        $table = Engine_Api::_()->getItemTable('socialcommerce_review');
        $rating_sum = $table->select()
            ->from($table->info('name'), new Zend_Db_Expr('SUM(rate_number)'))
            ->group('item_id')
            ->where('item_id = ?', $this->getIdentity())
            ->where('user_id <> ?', $this->owner_id)
            ->where('type = \'product\'')
            ->query()
            ->fetchColumn(0);

        $total = $this->ratingCount();
        if ($total)
            $rating = $rating_sum / $total;
        else
            $rating = 0;

        return $rating;
    }

    public function getPretaxPrice()
    {
        return $this->price;
    }

    public function getCurrency()
    {
        return Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
    }

    public function getCategory()
    {
        $category = Engine_Api::_()->getItem('socialcommerce_category', $this->category);
        if ($category) {
            return $category;
        }
    }

    public function ratingCount()
    {
        $table = Engine_Api::_()->getItemTable('socialcommerce_review');
        $select = $table->select()
            ->where('item_id = ?', $this->getIdentity())
            ->where('user_id <> ?', $this->owner_id);
        $row = $table->fetchAll($select);
        $total = count($row);
        return $total;
    }

    public function getCurrentAvailable()
    {
        if ($this->available_quantity == 0) {
            if ($this->max_qty_purchase == 0) {
                $str = 'unlimited';
                return $str;
            } else {
                return $this->max_qty_purchase;
            }
        } else {
            $quantity = $this->available_quantity - $this->sold_qty;
            if ($this->max_qty_purchase == 0) {
                return $quantity;
            } else {
                if ($quantity >= $this->max_qty_purchase) {
                    return $this->max_qty_purchase;
                } else {
                    return $quantity;
                }
            }
        }
    }

    public function setQuantity($qty)
    {
        $this->_qty += $qty;
        return $this;
    }

    public function getTotalAmount()
    {
        return $this->getPrice() * $this->_qty;
    }

    public function getItemTaxAmount()
    {
        $pretax_price = $this->getPretaxPrice();
        $item_tax_amount = round(($pretax_price * $this->getTaxPercentage()) / 100, 2);
        return $item_tax_amount;
    }

    public function getTaxPercentage()
    {
        return Engine_Api::_()->getApi('settings', 'core')->getSetting('tax', 0);
    }

    public function getStall()
    {
        return Engine_Api::_()->getItem('socialcommerce_stall', $this->stall_id);
    }

    public function getPrice()
    {
//        $pretax_price = $this->getPretaxPrice();
//        $item_tax_amount =  round( ($pretax_price * $this->tax_percentage)/100,2);
//        $price = $item_tax_amount + $pretax_price;
        return $this->price;
    }

    public function approve()
    {
        if ($this->approve_status == 'waiting') {
            $this->approve_status = 'approved';
            $this->save();
        }
        return $this;
    }

    public function deny()
    {
        if ($this->approve_status == 'waiting') {
            $this->approve_status = 'denied';
            $this->save();
        }
        return $this;
    }

    public function feature()
    {
        $this->featured = 1;
        $this->save();
        return $this;
    }

    public function unfeature()
    {
        $this->featured = 0;
        $this->save();
        return $this;
    }

    public function getQuantity()
    {
        return $this->_qty;
    }

    public function isEditable()
    {
        return Engine_Api::_()->user()->getViewer()->getIdentity();
    }

    /**
     * Gets a proxy object for the comment handler
     *
     * @return Engine_ProxyObject
     * */
    public function comments()
    {
        return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
    }

    /**
     * Gets a proxy object for the like handler
     *
     * @return Engine_ProxyObject
     * */
    public function likes()
    {
        return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
    }
}