<?php

class Socialcommerce_IndexController extends Core_Controller_Action_Standard
{
    public function indexAction()
    {
        $this->_helper->content
            //->setNoRender()
            ->setEnabled();
    }

    public function getMyLocationAction()
    {
        $latitude = $this->_getParam('latitude');
        $longitude = $this->_getParam('longitude');
        $values = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=true");
        echo $values;
        die;
    }

    public function directionAction()
    {
        $this->view->latitude = $lat = $this -> _getParam('lat', 0);
        $this->view->longitude = $long = $this -> _getParam('long', 0);
        $this->view->location = $location = $this -> _getParam('location', 0);
    }

    public function browseAction()
    {
        $this->_helper->content
            //->setNoRender()
            ->setEnabled();
    }

    public function testmailAction()
    {
        $orderItemTable = Engine_Api::_()->getDbtable('orderItems', 'socialcommerce');
        $ttTableName = $orderItemTable->info('name');
        $select = $orderItemTable->select();

        $select->from($ttTableName, array("$ttTableName.*"))
            ->setIntegrityCheck(false);
        $orderItems = $orderItemTable->fetchAll($select);
        if($orderItems != NULL) {
            $buyerparams['deal_dodcontent'] = $this->getDodContent($orderItems);
            $buyerparams['verify_code'] = 'xxx';
            $buyerparams['unsubscribe_link'] = 'groupbuy/subscription/unsubscribe/code/xxx';
            $buyerparams['buyer_name'] = 'Nguyen Chi Thanh';
            $buyerparams['buyer_email'] = 'nguyenthanh4794@gmail.com';
        }
        Engine_Api::_()->getApi('mail', 'socialcommerce')->send('nguyenthanh4794@gmail.com', 'stall_purchasebuyer', $buyerparams);;
        return;
    }

    public function getDodContent($products){
        $translate = Zend_Registry::get('Zend_Translate');
        $this->products =  $products;
        $this->contacts =  array();
        $this->uri = $this->getFullUri();
        $this->site_name = Engine_Api::_()->getApi('settings', 'core')->getSetting('core_general_site_title', $translate->translate('_SITE_TITLE'));
        $filename = APPLICATION_PATH . '/application/modules/Socialcommerce/views/scripts/mail/dod_content.tpl';
        ob_start();
        include $filename;
        $result = ob_get_clean();
        return $result;
    }

    public function getFullUri() {
        $host = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off") ? 'https' : 'http';
        $port = (isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80);
        $uri = $proto . '://' . $host;
        if ((('http' == $proto) && (80 != $port)) || (('https' == $proto) && (443 != $port)))
        {
            $uri .= ':' . $port;
        }
        return $uri;
    }
}
