<?php if ($this->account): ?>
<div class="socialcommerce_my_account">
    <div>
        <div class="socialcommerce_my_account_title"><?php echo $this->translate('My Account'); ?></div>
        <div class="socialcommerce_my_account_info_block">
            <div class="socialcommerce_my_account_block_title"><?php echo $this->translate('payment information')?></div>
            <?php $info_user = $this->account; ?>
            <div class="socialcommerce_my_account_block_account">
                <ul>
                    <li>
                        <span><?php echo $this->translate('gateway')?></span>
                        <span><?php echo $this->translate('paypal')?></span>
                    </li>
                    <li>
                        <span><?php echo $this->translate('Name')?></span>
                        <span><?php echo $info_user['name'] ?></span>
                    <li>
                        <span><?php echo $this->translate('paypal account')?></span>
                        <span><?php echo $info_user['account_username'] ?></span>
                    </li>

                    <a class="smoothbox" href="<?php echo $this->url(array('action' => 'create-paypal-account', 'controller' => 'seller', 'id' =>  $info_user['paypalaccount_id']),'socialcommerce_general'); ?>"><i class="ynicon yn-pencil"></i></a>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="tip">
    <span><?php echo $this->translate("You do not have paypal account yet."); ?>
    <?php echo $this->translate('Click here to %1$screate%2$s one!', '<a class="smoothbox" href="'.$this->url(array('action' => 'create-paypal-account', 'controller' => 'seller'), 'socialcommerce_general').'">', '</a>'); ?>
    </span>
</div>
<?php endif; ?>
<a style="display: none" class="smoothbox" href="<?php echo $this->url(array('action' => 'testmail', 'controller' => 'index'),'socialcommerce_general'); ?>">Send email</a>