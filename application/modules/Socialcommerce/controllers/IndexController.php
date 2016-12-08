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
}
