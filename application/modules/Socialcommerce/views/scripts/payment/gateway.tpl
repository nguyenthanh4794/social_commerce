<div class="contentbox">
    <form onsubmit="return onBeforeSubmit();" method="post">
        <div class="socialcommerce_checkout_payment">
            <div>
                <div class="socialcommerce_payment_title"><?php echo $this->translate('check out') ?></div>
                <div>
                    <div class="socialcommerce_payment_bottom_title"><?php echo $this->translate('payment') ?></div>
                    <div class="socialcommerce_payment_bottom_label"><?php echo $this->translate('Choose a payment method') ?></div>
                </div>
                <div id="not_support" class="tip" style="display: none">
                    <span><?php echo $this->translate('We will support this method soon.')?></span>
                </div>
                <div class="socialcommerce_payment_items">
                    <?php $gateways = Engine_Api::_()->getDbTable('gateways', 'payment')->getEnabledGateways(); ?>
                    <ul class="yn-clearfix">
                        <?php $i = 0; ?>
                        <?php foreach ($gateways as $gateway): ?>
                        <li id="socialcommerce_payment_<?php echo strtolower($gateway->getTitle()) ?>" class="<?php echo (!$i++)?'payment_active':'' ?>">
                            <div>
                                <div class="socialcommerce_payment_<?php echo strtolower($gateway->getTitle()) ?>"></div>
                                <div><?php echo $this->translate($gateway->getTitle())?></div>
                            </div>
                        </li>
                        <?php endforeach; ?>

                        <li id="socialcommerce_payment_virtual" class="virtual">
                            <div>
                                <div class="socialcommerce_payment_virtual"></div>
                                <div><?php echo $this->translate('Vitual Money')?></div>
                            </div>
                        </li>

                        <li id="socialcommerce_payment_cash" class="">
                            <div>
                                <div class="socialcommerce_payment_cash"></div>
                                <div><?php echo $this->translate('Cash on Delivery')?></div>
                            </div>
                        </li>
                    </ul>
                    <div class="socialcommerce_payment_button">
                        <button type="sumbit"><?php echo $this->translate('Next').'<i class="ynicon yn-arr-right"></i>'?></button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="gateway" id="socialcommerce_payment_method_id">
    </form>
</div>


<script type="text/javascript">
    window.addEvent('domready', function(){
        $$('.socialcommerce_payment_items ul li').removeEvent('click').addEvent('click', function(){
            this.getSiblings('li').removeClass('payment_active');
            this.addClass('payment_active');

            $('socialcommerce_payment_method_id').value = this.id.substring(23);

        });

        $$('.socialcommerce_payment_items ul li').each(function(el){
            if(el.hasClass('payment_active'))
                $('socialcommerce_payment_method_id').value = el.id.substring(23);
        });
    });
    var notice = new Fx.Reveal($('not_support'), {duration: 300, mode: 'vertical'});
    function onBeforeSubmit() {
        if($('socialcommerce_payment_method_id').value === 'paypal') {
            return true;
        } else {
            notice.toggle();
            return false;
        }
    }
</script>