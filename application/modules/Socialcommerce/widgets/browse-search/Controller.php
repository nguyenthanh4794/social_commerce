<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Controller.php 2015-01-22 00:00:53Z shaun $
 * @author     John Boehr <john@socialengine.com>
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Socialcommerce_Widget_BrowseSearchController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $location = $request -> getParam('location', '');

        // Prepare form
        $this->view->form = $form = new Socialcommerce_Form_Search(array(
            'location' => $location,
        ));

        if ($request->getModuleName() != 'socialcommerce')
            return $this->setNoRender();

        $controller = $request->getControllerName();
        if ($controller == 'index')
        {
            $form -> setAction(Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array('action' => 'browse'), 'socialcommerce_general', true));
        } else {
            if ($controller == 'product')
                $form -> setAction(Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array('action' => 'browse'), 'socialcommerce_specific', true));
            else
                $form -> setAction(Zend_Controller_Front::getInstance() -> getRouter() -> assemble(array('controller' => 'stall', 'action' => 'browse'), 'socialcommerce_general', true));
        }

        $this->view->headScript()->appendFile("https://maps.googleapis.com/maps/api/js?key=". Engine_Api::_() -> getApi('settings', 'core') -> getSetting('yncore.google.api.key', 'AIzaSyB3LowZcG12R1nclRd9NrwRgIxZNxLMjgc')."&v=3.exp&libraries=places");
        $p = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $form->populate($p);
    }
}
