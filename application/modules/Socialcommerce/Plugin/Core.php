<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/21/2016
 * Time: 11:02 PM
 */
class Socialcommerce_Plugin_Core
{
    public function onItemCreateAfter($event)
    {
        $request = Zend_Controller_Front::getInstance() -> getRequest();
        $payload = $event -> getPayload();
        if (!is_object($payload))
        {
            return;
        }
        if(!$request)
        {
            return;
        }

        $stall_id = $request -> getParam("stall_id", $request -> getParam("subject_id", null));
        $type = $request -> getParam("parent_type", null);

        $view = Zend_Registry::get('Zend_View');


        if ($type == 'socialcommerce_stall')
        {
            $owner_id = $stall_id;
            $stall = Engine_Api::_() -> getItem('socialcommerce_stall', $stall_id);
            $owner_type = 'socialcommerce_stall';
            switch ($payload -> getType())
            {
                case 'event':
                case 'video':
                    $video = Engine_Api::_()->getItem('video', $payload -> getIdentity());
                    $video -> parent_type = 'socialcommerce_stall';
                    $video -> parent_id = $stall_id;
                    $video -> save();
                    if($payload -> type == 0)
                        $key = 'socialcommerce_predispatch_url: video.index.manage';
                    else
                        $key = 'socialcommerce_predispatch_url: video.index.view';

                    $value = $view -> url(array(
                        'controller' => 'video',
                        'action' => 'manage',
                        'subject' => $stall->getGuid(),
                    ), 'socialcommerce_extended', true);
                    $_SESSION[$key] = $value;
                    break;

            }
        }
    }

    public function addActivity($event)
    {
        $payload = $event -> getPayload();
        $subject = $payload['subject'];
        $object = $payload['object'];

        // Only for object=business
        if ($object instanceof Socialcommerce_Model_Stall)
        {
            $event -> addResponse(array(
                'type' => 'socialcommerce_stall ',
                'identity' => $object -> getIdentity()
            ));
        }
    }

    public function getActivity($event)
    {
        // Detect viewer and subject
        $payload = $event -> getPayload();
        $user = null;
        $subject = null;
        if ($payload instanceof User_Model_User)
        {
            $user = $payload;
        }
        else
            if (is_array($payload))
            {
                if (isset($payload['for']) && $payload['for'] instanceof User_Model_User)
                {
                    $user = $payload['for'];
                }
                if (isset($payload['about']) && $payload['about'] instanceof Core_Model_Item_Abstract)
                {
                    $subject = $payload['about'];
                }
            }
        if (null === $user)
        {
            $viewer = Engine_Api::_() -> user() -> getViewer();
            if ($viewer -> getIdentity())
            {
                $user = $viewer;
            }
        }
        if (null === $subject && Engine_Api::_() -> core() -> hasSubject())
        {
            $subject = Engine_Api::_() -> core() -> getSubject();
        }

        // Get feed settings
        $content = Engine_Api::_() -> getApi('settings', 'core') -> getSetting('activity.content', 'everyone');

//        // Get event memberships
//        if ($user)
//        {
//            $data = Engine_Api::_() -> getDbtable('membership', 'socialcommerce') -> getMembershipsOfIds($user);
//            if (!empty($data) && is_array($data))
//            {
//                $event -> addResponse(array(
//                    'type' => 'stall',
//                    'data' => $data,
//                ));
//            }
//        }
    }
}