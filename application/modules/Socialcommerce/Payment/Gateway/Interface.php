<?php

interface Socialcommerce_Payment_Gateway_Interface {
	
	/**
     * Process payment request
     *
     * @param Socialcommerce_Payment_Request_Interface $request
     * @throws Socialcommerce_Payment_Extension
     * @return Socialcommerce_Payment_Response_Interface
     */
    public function process(Socialcommerce_Payment_Request_Interface $request);

    /**
     * Check if action is available on gateway level
     *
     * @param string $action
     * @return boolean
     */
    public function isActionAvailable($action);
}
