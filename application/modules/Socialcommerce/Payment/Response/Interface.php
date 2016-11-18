<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/9/2016
 * Time: 12:48 AM
 */
interface Socialcommerce_Payment_Response_Interface
{
    public function __construct($status);
    public function getStatus();
    public function isSuccess();
}