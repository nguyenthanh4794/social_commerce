<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/21/2016
 * Time: 10:37 PM
 */
class Socialcommerce_Model_DbTable_Mappings extends Engine_Db_Table
{
    protected $_name = 'socialcommerce_mappings';

    public function getAlbumsPaginator($params = array())
    {
        $paginator = Zend_Paginator::factory($this->getAlbumsSelect($params));
        if (!empty($params['page'])) {
            $paginator->setCurrentPageNumber($params['page']);
        }
        if (!empty($params['limit'])) {
            $paginator->setItemCountPerPage($params['limit']);
        }
        return $paginator;
    }

    public function getAlbumsSelect($params = array())
    {
        //Get album table
        $table_music = Engine_Api::_()->getItemTable($params['ItemTable']);
        $select = $table_music->select();
        if (!isset($params['ItemTable'])) {
            $params['ItemTable'] = 'music_playlist';
        }
        $ids = $this->getItemIdsMapping($params['ItemTable'], $params);
        if (!empty($ids)) {
            if ($params['ItemTable'] == 'mp3music_album') {
                $select->where('album_id IN (?)', $ids);
            } else {
                $select->where('playlist_id IN (?)', $ids);
            }
        } else {
            if ($params['ItemTable'] == 'mp3music_album') {
                $select->where('album_id IN (0)', $ids);
            } else {
                $select->where('playlist_id IN (0)', $ids);
            }
        }

        //Search
        if (!empty($params['search'])) {
            $select->where('title LIKE ? OR description LIKE ?', '%' . $params['search'] . '%');
        }

        // Order
        switch ($params['order']) {
            case 'comment':
                $select->order('comment_count DESC');
                break;
            case 'play':
                $select->order('play_count DESC');
                break;
            case 'recent':
            default:
                $select->order('creation_date DESC');
                break;
        }
        return $select;
    }

    public function getVideosPaginator($params = array(), $order_by = true)
    {
        $paginator = Zend_Paginator::factory($this->getVideosSelect($params, $order_by));
        if (!empty($params['page'])) {
            $paginator->setCurrentPageNumber($params['page']);
        }
        if (!empty($params['limit'])) {
            $paginator->setItemCountPerPage($params['limit']);
        }
        return $paginator;
    }

    public function getVideosSelect($params = array(), $order_by = true)
    {
        $table = Engine_Api::_()->getItemTable('video');
        $rName = $table->info('name');
        $select = $table->select()->from($rName)->setIntegrityCheck(false);

        $select->where('parent_type = \'socialcommerce_stall\' AND parent_id = ?', $params['stall_id']);

        if (!empty($params['orderby'])) {
            if (isset($params['order'])) {
                $order = $params['order'];
            } else {
                $order = '';
            }
            switch ($params['orderby']) {
                case 'most_liked' :
                    $likeTable = Engine_Api::_()->getDbTable('likes', 'core');
                    $likeTableName = $likeTable->info('name');
                    $likeVideoTableSelect = $likeTable->select()->where('resource_type = ?', 'video');
                    $select->joinLeft($likeVideoTableSelect, "t.resource_id = $rName.video_id");
                    $select->group("$rName.video_id");
                    $select->order("count(t.like_id) DESC");
                    break;
                case 'most_commented' :
                    $commentTable = Engine_Api::_()->getDbTable('comments', 'core');
                    $commentTableName = $commentTable->info('name');
                    $commentVideoTableSelect = $commentTable->select()->where('resource_type = ?', 'video');
                    $select->join($commentVideoTableSelect, "t.resource_id = $rName.video_id");
                    $select->group("$rName.video_id");
                    $select->order("count(t.comment_id) DESC");
                    break;
                case 'featured' :
                    $select->where('featured = ?', 1);
                    $select->order("$rName.creation_date DESC");
                    break;
                default :
                    $select->order("$rName.{$params['orderby']} DESC");
            }
        } else {
            if ($order_by) {
                $select->order("$rName.creation_date DESC");
            }
        }

        if (!empty($params['title'])) {
            $select->where("$rName.title LIKE ?", "%{$params['title']}%");
        }

        if (!empty($params['user_id']) && is_numeric($params['user_id'])) {
            $select->where($rName . '.owner_id = ?', $params['user_id']);
        }

        if (!empty($params['user']) && $params['user'] instanceof User_Model_User) {
            $select->where($rName . '.owner_id = ?', $params['user_id']->getIdentity());
        }

        return $select;
    }

    public function getOwner($item)
    {
        $owner = $item->getOwner();
        return $owner;
    }
}