<?php
if (!empty($this->buy_for_friend))
$page_description = $this->translate("Select your friend's information listed below, or add a new section. Your choice will be used for the delivery.");
else
$page_description = $this->translate('Select your information listed below, or add a new section. Your choice will be used for the delivery.');
?>
<div class="socialcommerce_checkout_payment">
    <div>
        <div class="socialcommerce_payment_title"><?php echo $this->translate('check out')?></div>
        <div class="socialcommerce_shipping_top_info">
            <div class="socialcommerce_shipping_top_left">
                <div><?php echo $this->translate('shipping information').' '.'('. $this->locale()->toNumber(count($this->shipping_infos)) .')'?></div>
                <div><?php echo $page_description ?></div>
            </div>
            <div class="socialcommerce_shipping_top_right">
                <a href="<?php echo $this->url(array('controller' => 'shipping-address', 'action' => 'create'), 'socialcommerce_extended', true) ?>" class="smoothbox"><?php echo '<i class="ynicon yn-plus"></i>'.' '.$this->translate('Add Another Information')?></a>
            </div>
        </div>
    </div>

    <form action="<?php echo $this->escape($this->form->getAction()) ?>" method="post" id="socialcommerce_shipping_info_form">
        <?php if (count($this->shipping_infos) <= 0): ?>
        <ul class="form-errors">
            <li>
                <ul class="errors">
                    <li>You must add and select an shipping information to continue</li>
                </ul>
            </li>
        </ul>
        <?php elseif ($this->error): ?>
        <ul class="form-errors">
            <li>
                <ul class="errors">
                    <li>You must select an shipping information to continue</li>
                </ul>
            </li>
        </ul>
        <?php endif; ?>
        <div class="socialcommerce_shipping_items yn-clearfix">
            <ul class="yn-clearfix">
                <?php foreach ($this->shipping_infos as $item): ?>
                <?php $info = json_decode($item->value); ?>
                <li class="socialcommerce_shipping_parent">
                    <div>
                        <div>
                            <div class="socialcommerce_shipping_item_title"><?php echo $info->fullname; ?></div>
                            <div class="socialcommerce_shipping_item_info">
                                <div><span><?php echo $this->translate('address').': '?></span><?php echo $info->street ?></div>
                                <div><span><?php echo $this->translate('phone').': '?></span><?php echo $info->phone ?></div>
                                <div><span><?php echo $this->translate('email').': '?></span><?php echo $info->email ?></div>
                            </div>
                        </div>

                        <div class="yn-dropdown-block">
                            <div class="yn-dropdown">
                                <span class="yn-dropdown-btn"><i class="ynicon yn-arr-down"></i></span>
                                <ul class="yn-dropdown-menu">
                                    <li>
                                        <a class="smoothbox" href="<?php echo $this->url(array('controller' => 'shipping-address', 'action' => 'create', 'shippinginformation_id' => $item->getIdentity()), 'socialcommerce_extended', true) ?>"><i class="ynicon yn-pencil"></i><?php echo $this->translate('Edit information')?></a>
                                    </li>
                                    <li>
                                        <a class="smoothbox" href="<?php echo $this->url(array('controller' => 'shipping-address', 'action' => 'delete', 'shippinginformation_id' => $item->getIdentity()), 'socialcommerce_extended', true) ?>"><i class="ynicon yn-trash-alt"></i><?php echo $this->translate('Delete information')?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="socialcommerce_shipping_background" style="background-image: url(application/modules/Socialcommerce/externals/images/shipping.svg);"></div>
                        <input type="radio" name="shippinginformation_id" id="<?php echo $item->getIdentity() ?>" value="<?php echo $item->getIdentity() ?>" style="display: none;" />
                        <label for="<?php echo $item->getIdentity() ?>" class="ynicon yn-check select_shipping"></label>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php echo $this->form->shipping_note; ?>
            <input type="hidden" name="quantity" id="socialcommerce_quantity" value="">
            <div class="socialcommerce_payment_button">
                <button name="execute" id="execute" type="submit"><?php echo $this->translate('Next').'<i class="ynicon yn-arr-right"></i>'?></button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    var note_remain_text = "<?php echo $this->translate('Remaining characters') ?>";

    window.addEvent('domready',function(){
        ynDropdown();
        ynOuterClick();

        // SELECT THE FIRST SHIPPING ADDRESS
        if ($$('.socialcommerce_shipping_parent').length) {
            var firstShippingInfo = $$('.socialcommerce_shipping_parent')[0];
            firstShippingInfo.addClass('shipping_active');
            firstShippingInfo.getElement('input').set('checked', true);
        }

        // EVENT FOR REMAINING CHARACTERS
        $('shipping_note').addEvent('input', function() {
            var note_remain = this.getSiblings('p')[0];
            note_remain.set('html', note_remain_text + ': ' + (250 - this.get('value').length) + '/250');

        });

        $$('.select_shipping').removeEvents('click').addEvent('click', selectShippingInfo);

        refreshNextButton();
    })

    function selectShippingInfo(ev) {
        var parent = ev.target.getParent('.socialcommerce_shipping_parent');
        parent.addClass('shipping_active');
        parent.getSiblings('li').removeClass('shipping_active');
        refreshNextButton();
    }

    function refreshNextButton() {
        var nextButton = $$('.socialcommerce_payment_button #submit');
        nextButton.set('disabled', ($$('input[name=shippinginformation_id]:checked]').length) ? false : true);
    }

    $('socialcommerce_shipping_info_form').addEvent('submit', function(){
        if($$('.socialcommerce_checkout_quantity')[0])
            $('socialcommerce_quantity').value = $$('.socialcommerce_checkout_quantity')[0].value;
    })
</script>