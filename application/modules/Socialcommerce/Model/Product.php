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

    public function allowAction($action,$user = null)
    {
        if($user == null)
            $user = Engine_Api::_()->user()->getViewer();
        switch ($action) {
            case 'view':
                if(in_array($this->status, array('draft', 'pending', 'denied', 'deleted')) && $user->getIdentity() != $this->owner_id && !$user->isAdmin())
                    return false;
                return $this -> authorization() -> isAllowed(null, 'view');
            case 'edit':
                return $this -> authorization() -> isAllowed(null, 'edit');
            case 'delete':
                if(!$this -> authorization() -> isAllowed(null, 'delete'))
                    return false;
                if($this->status == 'ongoing')
                    return false;
                if($user->getIdentity() != $this->user_id && !$user->isAdmin())
                    return false;
                return true;
            case 'publish':
                if(!$this -> authorization() -> isAllowed(null, 'can_sell_deal'))
                    return false;
                if($this->status != 'draft')
                    return false;
                return true;
            case 'feature':
                if($user->getIdentity() != $this->user_id && !$user->isAdmin())
                    return false;
                if(!in_array($this->status, array('pending', 'upcoming', 'ongoing', 'paused')))
                    return false;
                return true;
            case 'statistic':
                if($user->getIdentity() != $this->user_id && !$user->isAdmin())
                    return false;
                return true;
            case 'clone':
                if($this->user_id != $user->getIdentity())
                    return false;
                return true;
            case 'pause':
                if(! $this -> authorization() -> isAllowed(null, 'pause'))
                    return false;
                if($this->user_id != $user->getIdentity())
                    return false;
                if($this->status != 'ongoing')
                    return false;
                return true;
            case 'resume':
                if($this->user_id != $user->getIdentity())
                    return false;
                if($this->status != 'paused')
                    return false;
                return true;
            case 'unfeature':
                if(!$this->featured)
                    return false;
                if($user->getIdentity() != $this->user_id && !$user->isAdmin())
                    return false;
                return true;
            case 'mark':
                return (bool) $user->getIdentity();
            case 'buy':
                if($user->getIdentity() == $this->user_id)
                    return false;
                if($this->status != 'ongoing')
                    return false;
                return true;
            default:
                throw new Exception("Unsupported action", 1);
                return true;
        }
    }

    public function setPhoto($photo)
    {
        if ($photo instanceof Zend_Form_Element_File)
        {
            $file = $photo -> getFileName();
            $name = basename($file);
        }
        else if( $photo instanceof Storage_Model_File ) {
            $file = $photo->temporary();
            $name = $photo->name;
        }
        else if (is_array($photo) && !empty($photo['tmp_name'])) {
            $file = $photo['tmp_name'];
            $name = $photo['name'];
        } else {
            throw new User_Model_Exception('invalid argument passed to setPhoto');
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
        $params = array(
            'parent_type' => 'socialcommerce_product',
            'parent_id' => $this -> getIdentity(),
            'user_id' => $viewer -> getIdentity(),
        );

        // Save
        $storage = Engine_Api::_() -> storage();

        // Resize image (main)
        $image = Engine_Image::factory();
        $image -> open($file);
        $image -> resize(640, 360) -> write($path . '/m_' . $name) -> destroy();

        // Resize image (profile)
        $image = Engine_Image::factory();
        $image -> open($file);
        $image-> resize(420, 236) -> write($path . '/p_' . $name) -> destroy();

        // Store
        $iMain = $storage -> create($path . '/m_' . $name, $params);
        $iProfile = $storage -> create($path . '/p_' . $name, $params);

        $iMain -> bridge($iProfile, 'thumb.profile');

        // Remove temp files
        @unlink($path . '/p_' . $name);
        @unlink($path . '/m_' . $name);
        //@unlink($file);

        // Update row
        $this -> modified_date = date('Y-m-d H:i:s');
        $this -> photo_id = $iMain -> file_id;
        $this -> save();

        return $this;
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

    public function sendEmailToFriends($recipients, $message)
    {
        $viewer = Engine_Api::_() -> user() -> getViewer();
        // Check recipients
        if( is_string($recipients) )
        {
            $recipients = preg_split("/[\s,]+/", $recipients);
        }
        if( is_array($recipients) )
        {
            $recipients = array_map('strtolower', array_unique(array_filter(array_map('trim', $recipients))));
        }
        if( !is_array($recipients) || empty($recipients) )
        {
            return 0;
        }

        // Check message
        $message = trim($message);
        $sentEmails = 0;
        $photo_url = ($this->getPhotoUrl('thumb.profile')) ? $this->getPhotoUrl('thumb.profile') : 'application/modules/Socialcommerce/externals/images/nophoto_product_thumb_profile.png';
        foreach( $recipients as $recipient )
        {
            $mailType = 'socialcommerce_email_to_friends';
            $mailParams = array(
                'host' => $_SERVER['HTTP_HOST'],
                'email' => $recipient,
                'date' => time(),
                'sender_email' => $viewer->email,
                'sender_title' => $viewer->getTitle(),
                'sender_link' => $viewer->getHref(),
                'sender_photo' => $viewer->getPhotoUrl('thumb.icon'),
                'message' => $message,
                'object_link' => $this->getHref(),
                'object_title' => $this->title,
                'object_photo' => $photo_url,
                'object_description' => $this->description,
            );

            Engine_Api::_()->getApi('mail', 'core')->sendSystem(
                $recipient,
                $mailType,
                $mailParams
            );
            $sentEmails++;
        }
        return $sentEmails;
    }
}