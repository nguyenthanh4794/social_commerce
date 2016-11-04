<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 10/15/2016
 * Time: 12:22 PM
 */
class Socialcommerce_Plugin_Menus
{
    public function canCreateProduct()
    {
        return true;
    }

    public function canCreateStall()
    {
        return true;
    }

    public function canCreateListing()
    {
        return true;
    }
}