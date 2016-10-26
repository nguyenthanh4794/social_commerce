<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/15/2016
 * Time: 10:30 AM
 */
class Socialcommerce_Model_Stall extends Core_Model_Item_Abstract
{
    protected $_type = 'socialcommerce_stall';

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

    public function isViewable()
    {
        return true;
    }

    public function getCategory()
    {
        $category = Engine_Api::_()->getItem('socialcommerce_category', $this->category);

        if ($category) {
            return $category;
        }
    }

    public function getRating()
    {
        $table = Engine_Api::_()->getItemTable('socialcommerce_review');

        $rating_sum = $table->select()
            ->from($table->info('name'), new Zend_Db_Expr('SUM(rate_number)'))
            ->group('item_id')
            ->where('item_id = ?', $this->getIdentity())
            ->where('user_id <> ?', $this->owner_id)
            ->where('type = \'stall\'')
            ->query()
            ->fetchColumn(0)
        ;

        $total = $this->ratingCount();
        if ($total)
            $rating = $rating_sum / $total;
        else
            $rating = 0;
        echo $rating;

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

    public function getHref($params = array()) {
        $slug = $this -> getSlug();
        $params = array_merge(array(
            'route' => 'socialcommerce_profile',
            'id' => $this->getIdentity(),
            'slug' => $slug,
        ),
            $params);
        $route = $params['route'];
        unset($params['route']);
        return Zend_Controller_Front::getInstance()->getRouter()
            ->assemble($params, $route, true);
    }

    public function setPhoto($photo, $field)
    {
        if( $photo instanceof Zend_Form_Element_File ) {
            $file = $photo->getFileName();
            $fileName = $file;
        } else if( $photo instanceof Storage_Model_File ) {
            $file = $photo->temporary();
            $fileName = $photo->name;
        } else if( $photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id) ) {
            $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
            $file = $tmpRow->temporary();
            $fileName = $tmpRow->name;
        } else if( is_array($photo) && !empty($photo['tmp_name']) ) {
            $file = $photo['tmp_name'];
            $fileName = $photo['name'];
        } else if( is_string($photo) && file_exists($photo) ) {
            $file = $photo;
            $fileName = $photo;
        } else {
            throw new User_Model_Exception('invalid argument passed to setPhoto');
        }

        if( !$fileName ) {
            $fileName = $file;
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        $name = basename($file);
        $extension = ltrim(strrchr(basename($fileName), '.'), '.');
        $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
        $params = array(
            'parent_type' => $this->getType(),
            'parent_id' => $this->getIdentity(),
            'user_id' => $viewer->getIdentity(),
            'name' => basename($fileName),
        );

        // Save
        $filesTable = Engine_Api::_()->getDbtable('files', 'storage');

        // Resize image (main)
        $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
            ->autoRotate()
            ->resize(720, 720)
            ->write($mainPath)
            ->destroy();

        // Resize image (profile)
        $profilePath = $path . DIRECTORY_SEPARATOR . $base . '_p.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
            ->autoRotate()
            ->resize(200, 400)
            ->write($profilePath)
            ->destroy();

        // Resize image (normal)
        $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
            ->autoRotate()
            ->resize(140, 160)
            ->write($normalPath)
            ->destroy();

        // Resize image (icon)
        $squarePath = $path . DIRECTORY_SEPARATOR . $base . '_is.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
            ->autoRotate();

        $size = min($image->height, $image->width);
        $x = ($image->width - $size) / 2;
        $y = ($image->height - $size) / 2;

        $image->resample($x, $y, $size, $size, 48, 48)
            ->write($squarePath)
            ->destroy();

        // Store
        $iMain = $filesTable->createFile($mainPath, $params);
        $iProfile = $filesTable->createFile($profilePath, $params);
        $iIconNormal = $filesTable->createFile($normalPath, $params);
        $iSquare = $filesTable->createFile($squarePath, $params);

        $iMain->bridge($iProfile, 'thumb.profile');
        $iMain->bridge($iIconNormal, 'thumb.normal');
        $iMain->bridge($iSquare, 'thumb.icon');

        // Remove temp files
        @unlink($mainPath);
        @unlink($profilePath);
        @unlink($normalPath);
        @unlink($squarePath);

        // Update row
        $this->modified_date = date('Y-m-d H:i:s');
        if ($field == 'cover_id') {
            $this->cover_id = $iMain->file_id;
        } else {
            $this->photo_id = $iMain->file_id;
        }
        $this->save();

        return $this;
    }

    public function getCoverPhotoUrl($type = null)
    {
        if( empty($this->cover_id) || $this->cover_id == 0) {
            return null;
        }

        $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->cover_id, $type);

        if( !$file ) {
            return null;
        }

        return $file->map();
    }
}