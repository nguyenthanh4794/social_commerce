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
<style>
    .order_detail_information span.order_detail_status {
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 4px;
    }
    span.order_status_initial {
        color: #2ea9ce !important;
        background-color: #d9edf7;
        border: 1px solid #2ea9ce;
    }
    span.order_status_shipping {
        color: #fff !important;
        background-color: #36a5ec;
        border: 1px solid #1574b2;
    }
    span.order_status_shipped {
        color: #cd1c1c !important;
        background-color: #f9e4e4;
        border: 1px solid #cd1c1c;
    }
    span.order_status_completed {
        background-color: #25b35a;
        color: #fff !important;
        border: 1px solid #078034;
    }
    .socialcommerce_order_detail_total.order_detail_information {
        left: 0;
    }
    .socialcommerce_order_detail_total.order_detail_information ul li {
        max-width: 400px;
    }
</style>
<div class='clear'>
    <div class='settings'>
        <form class="global_form">
            <div>
                <p>
                    <?php //echo $this->translate("SOCIALCOMMERCE_VIEWS_SCRIPTS_ADMINORDER_INDEX_DESCRIPTION") ?>
                </p>
                <br />

                <div class="socialcommerce_order_detail_total order_detail_information">
                    <div class="socialcommerce_order_detail_info_title"><?php echo $this->translate("Order Information");?></div>
                    <ul>
                        <li>
                            <div>
                                <span class="product_title"><?php echo $this->translate('Order ID') ?></span>
                                <span class="product_unit_price"><?php echo $this->order_id; ?></span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <span class="product_title"><?php echo $this->translate('Order Status') ?></span>
                                <span class="order_detail_status order_status_<?php echo $this->order->order_status; ?>"><?php echo ucfirst($this->order->order_status); ?></span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <span class="product_title"><?php echo $this->translate('Payment Status') ?></span>
                                <span class="order_detail_status order_status_<?php echo $this->order->payment_status; ?>"><?php echo ucfirst($this->order->payment_status); ?></span>
                            </div>
                        </li>
                    </ul>
                </div>

                <br />

                <div class="profile_fields">
                    <div class="socialcommerce_order_detail_info_title">
                        <?php echo $this->translate('Shipping Address'); ?>
                    </div>
                    <div class="socialcommerce_order_detail_info_block">
                        <div class="clearfix">
                            <div class="socialcommerce_order_detail_info_left">
                                <div class="socialcommerce_order_detail_info_block_title"><?php echo $this->translate('Your contact information'); ?></div>
                                <div class="socialcommerce_order_detail_info_block_owner_name"><?php echo $this->aValuesShipping['fullname']; ?></div>
                                <div class="socialcommerce_order_detail_info_block_owner_address"><?php echo $this->aValuesShipping['street']; ?></div>
                                <div class="socialcommerce_order_detail_info_block_owner_email"><?php echo $this->aValuesShipping['email']; ?></div>
                                <div class="socialcommerce_order_detail_info_block_owner_number"><?php echo $this->aValuesShipping['phone']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php foreach($this->products as $key => $product): ?>
                <div class="socialcommerce_order_detail_info_block">
                    <div class="clearfix">
                        <div class="socialcommerce_order_detail_info_left min-width-none">
                            <div class="socialcommerce_order_detail_product_photo">
                                <span style="background-image: url('<?php echo $product->getPhotoUrl(); ?>');"></span>
                            </div>
                            <div class="socialcommerce_order_detail_product_info">
                                <div class="socialcommerce_order_detail_product_title"><?php echo $product->getTitle(); ?></div>
                                <div class="socialcommerce_order_detail_product_sub_info">
                                    <span><?php echo $this->translate('Category:') ?></span>
                                </div>
                                <div class="socialcommerce_order_detail_product_sub_info">
                                    <span><?php echo $this->translate('Seller:') ?></span>
                                    <?php echo $this->htmlLink($product->getOwner()->getHref(), $product->getOwner()->getTitle()) ?>
                                </div>
                                <div class="socialcommerce_order_detail_product_sub_info">
                                    <span><?php echo $this->translate('From:') ?></span>
                                    <?php echo $this->htmlLink($product->getStall()->getHref(), $product->getStall()->getTitle()) ?>
                                </div>
                            </div>
                        </div>
                        <div class="socialcommerce_order_detail_info_right min-width-none">
                            <ul>
                                <li>
                                    <div>
                                        <span class="product_title"><?php echo $this->translate('Unit price') ?></span>
                                        <span class="product_unit_price"><?php echo $this->currency($product->price)?></span>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <span class="product_title"><?php echo $this->translate('VAT') ?></span>
                                        <span>10%</span>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <span class="product_title"><?php echo $this->translate('Quantity') ?></span>
                                        <span><?php echo $this->locale()->toNumber($this->moreInfos[$key]['quantity'])?></span>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <span class="product_title"><?php echo $this->translate('Sub price') ?></span>
                                        <span class="product_price_sub"><?php echo $this->currency($this->moreInfos[$key]['total_amount'])?></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="socialcommerce_order_detail_total">
                    <div class="socialcommerce_order_detail_info_title"><?php echo $this->translate("Order Summary");?></div>
                    <ul>
                        <li>
                            <div>
                                <span class="product_title"><?php echo $this->translate('Items') ?></span>
                                <span class="product_unit_price"><?php echo $this->locale()->toNumber($this->order->quantity); ?></span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <span class="product_title"><?php echo $this->translate('Shipping & Handling Fee') ?></span>
                                <span><?php echo $this->currency(round(($this->order->shipping_amount + $this->order->handling_amount),2))?></span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <span class="product_title"><?php echo $this->translate('Order Total') ?></span>
                                <span class="product_price"><?php echo $this->currency($this->order->total_amount)?></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
</div>