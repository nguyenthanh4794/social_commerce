<h2><?php echo $this->translate("Social Commerce Plugin") ?></h2>
<?php if( count($this->navigation) ): ?>
<div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
</div>
<?php endif; ?>
<div class="clear">
    <div class="settings">
        <form enctype="application/x-www-form-urlencoded" class="global_form" action="<?php echo $this->formUrl ?>" method="post">
            <div>
                <div>
                    <h3><?php echo $this->translate("Send Money")
                        ?></h3>
                    <p class="form-description">
                        <?php echo $this->translate("ADMIN_SENDMONEY_TO_SELLER_DESCRITION")
                        ?>
                    </p>
                    <div class="form-elements">
                        <div id="store_currency-wrapper" class="form-wrapper">
                            <div class="form-wrapper">
                                <div class="form-label">
                                    <label class="optional">Amount</label>
                                </div>
                                <div id="store_product_rate-element" class="form-element">
                                    <?php echo $this->currency($this->request->request_amount, $this->currency) ?>
                                </div>
                            </div>
                            <div class="form-wrapper">
                                <div class="form-label">
                                    <label class="optional">Account</label>
                                </div>
                                <div id="store_product_rate-element" class="form-element">
                                    <?php echo $this->account->account_username ?>
                                </div>
                            </div>
                            <input type="hidden" name="no_shipping" value="1"/>
                            <input type="hidden" name="cmd" VALUE="_xclick">
                            <input type="hidden" name="business" VALUE=" <?php echo $this -> account -> account_username;?>">
                            <input type="hidden" name="amount" VALUE="<?php echo $this -> request -> request_amount;?>">
                            <input type="hidden" name="currency_code" VALUE="<?php echo $this -> currency;?>">
                            <input type="hidden" name="description" VALUE="">
                            <input type="hidden" name="response_message" VALUE="<?php echo $this->responseMessage;?>">
                            <input type="hidden" name="notify_url" value="<?php echo $this -> notifyUrl;?>"/>
                            <input type="hidden" name="return" value="<?php echo $this->returnUrl ?>"/>
                            <input type="hidden" name="cancel_return" value="<?php echo $this->cancelUrl ?>"/>
                            <div class="form-wrapper">
                                <div id="submit-label" class="form-label">
                                    &nbsp;
                                </div>
                                <div id="submit-element" class="form-element">
                                    <button name="submit" id="submit" type="submit">
                                        <?php echo $this->translate("Send Money") ?>
                                    </button> or <a href="<?php echo $this->url(array('action'=>'index'))?>"><?php echo $this->translate("Cancel") ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>