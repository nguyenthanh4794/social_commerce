<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/22/2016
 * Time: 12:06 AM
 */
class Socialcommerce_Plugin_Shutdown extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        // CHECK IF ADMIN
        if (substr($request -> getPathInfo(), 1, 5) == "admin")
        {
            return;
        }
        $view = Zend_Registry::get('Zend_View');
        $module = $request -> getModuleName();
        $controller = $request -> getControllerName();
        $action = $request -> getActionName();

        $key = 'socialcommerce_predispatch_url:' . $module . '.' . $controller . '.' . $action;
        if (isset($_SESSION[$key]) && $_SESSION[$key])
        {
            $url = $_SESSION[$key];
            header('location:' . $url);
            unset($_SESSION[$key]);
            @session_write_close();
            exit ;
        }

        $stall_session = new Zend_Session_Namespace('socialcommerce_stall');
        $stallId = $stall_session -> stallId;
        if(!$stallId)
        {
            return;
        }
        $stall = Engine_Api::_() -> getItem('socialcommerce_stall', $stallId);
        // check and redirect to stall manin page
        $redirect = true;
        $subjectId = 0;
        switch ($module)
        {
            case 'socialcommerce':
                switch ($controller)
                {
                    case 'profile':
                        $subjectId = $request -> getParam('id', 0);
                        break;
                    case 'review':
                        $subjectId = $stallId;
                        break;
                    case 'contact':
                        $subjectId = $stallId;
                        break;
                    case 'transaction':
                        $subjectId = $stallId;
                        break;
                    case 'photo':
                    case 'video':
                    case 'music':
                    case 'event':
                    case 'stall':
                    case 'index':
                        if(in_array($action, array('direction', 'logout-stall', 'warning', 'place-order', 'update-order', 'pay-credit', 'compose-message')))
                        {
                            $subjectId = $stallId;
                        }
                        break;
                }
                break;
            case 'activity':
            case 'core':
                if(in_array($controller, array('widget', 'tag', 'comment', 'report', 'link')))
                {
                    $subjectId = $stallId;
                }
                break;
            case 'album':
            case 'video':
            case 'music':
            case 'event':
        }

        if(($action == 'view' && !in_array($controller, array('photo', 'topic', 'folder')))
            || ($controller == 'profile' && $action == 'index' && in_array($module, array('event', 'ynevent')))
            || ($controller == 'view')
            || ($controller == 'album' && $action == 'album'
                || ($controller == 'profile' && $action == 'index' && $module == 'socialcommerce' && $subjectId != $stallId)
            )
        )
        {
            $return_url = '64-' . base64_encode($_SERVER['REQUEST_URI']);
            $url = $view -> url(array(
                'action' => 'warning',
                'subject' => $stall->getGuid(),
                'return_url' => $return_url,
            ), 'socialcommerce_general', true);
            header('location:' . $url);
            exit;
        }

        if($subjectId == $stallId)
        {
            $redirect = false;
        }
        if($redirect)
        {
            header('location:' . $stall -> getHref());
            exit;
        }
    }
}