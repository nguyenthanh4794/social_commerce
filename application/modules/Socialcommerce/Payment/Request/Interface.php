<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 11/8/2016
 * Time: 11:49 PM
 */
interface Socialcommerce_Payment_Request_Interface
{
    /**
     * Request action getter
     *
     * @return string
     */
    public function getAction();

    public function getOptions();

    /**
     * Method getter
     *
     * @return Socialcommerce_Payment_Method_Interface
     */
    public function getMethod();

    /**
     * Request order getter
     *
     * @return Socialcommerce_Payment_Order_Interface
     */
    public function getOrder();

    /**
     * Order setter
     *
     * @param Socialcommerce_Payment_Order_Interface $order
     */
    public function setOrder(Socialcommerce_Model_Order $order);

    /**
     * Request transaction getter
     *
     * @return Socialcommerce_Payment_Transaction
     */
    public function getTransaction();

    /**
     * Transaction setter
     *
     * @param Socialcommerce_Payment_Transaction $transaction
     */
    public function setTransaction(Socialcommerce_Payment_Transaction $transaction);
}