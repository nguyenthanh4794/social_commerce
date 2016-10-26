<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/23/2016
 * Time: 9:59 AM
 */
class Socialcommerce_Model_Product extends Core_Model_Item_Abstract
{
    protected $_type = 'socialcommerce_product';

    public function isNew() {
        $now = new DateTime();
        $creation_date = new DateTime($this->creation_date);
        $new_days = Engine_Api::_()->getApi('settings', 'core')->getSetting('products_new_days', 1);
        $diff = date_diff($creation_date, $now);
        $measure = ($diff->format('%a'));
        if ($measure <= $new_days) return true;
        return false;
    }

    public function getHref($params = array()) {
        $slug = $this -> getSlug();
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

    public function getRating() {
        $table = Engine_Api::_()->getItemTable('socialcommerce_review');
        $rating_sum = $table->select()
            ->from($table->info('name'), new Zend_Db_Expr('SUM(rate_number)'))
            ->group('item_id')
            ->where('item_id = ?', $this->getIdentity())
            ->where('user_id <> ?', $this->owner_id)
            ->where('type = \'product\'')
            ->query()
            ->fetchColumn(0)
        ;

        $total = $this->ratingCount();
        if ($total)
            $rating = $rating_sum / $total;
        else
            $rating = 0;

        return $rating;
    }

    public function ratingCount() {
        $table = Engine_Api::_()->getItemTable('socialcommerce_review');
        $select = $table->select()
            ->where('item_id = ?', $this->getIdentity())
            ->where('user_id <> ?', $this->owner_id);
        $row = $table->fetchAll($select);
        $total = count($row);
        return $total;
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
    public function comments() {
        return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
    }

    /**
     * Gets a proxy object for the like handler
     *
     * @return Engine_ProxyObject
     * */
    public function likes() {
        return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
    }
}