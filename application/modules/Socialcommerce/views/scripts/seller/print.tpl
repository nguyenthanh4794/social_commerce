<p>
    <?php //echo $this->translate("SOCIALCOMMERCE_VIEWS_SCRIPTS_ADMINORDER_INDEX_DESCRIPTION") ?>
</p>
<br />
<h3><?php echo $this->translate('Order: ') . $this->order_id;?></h3>
<br />

<?php foreach($this->products as $key => $product): ?>
<div class = "socialcommerce_review_product">
    <div class="socialcommerce_product_browse_photo">
        <?php echo $this->htmlLink($product->getHref(), $this->itemPhoto($product, 'thumb.normal')) ?>
    </div>
    <div class = "socialcommerce_review_product_info">
        <div class = "socialcommerce_review_product">
            <span class='product_browse_info_title'><?php echo $product -> title ?></span>
            <span class='product_browse_info_date'>
				              <?php echo $this->translate('posted by');?> <?php echo $this->htmlLink($product->getOwner()->getHref(), $product->getOwner()->getTitle()) ?>
                <?php if ($product->getStall()): ?>
				              - <?php echo $this->translate('stall: ');?> <?php echo $this->htmlLink($product->getStall()->getHref(), $product->getStall()->getTitle()) ?>
                <?php endif; ?>
				            </span>
        </div>
        <div class = "socialcommerce_review_product"><span class ="socialcommerce_review_span_title"><?php echo $this->translate("Quantity")?></span>:<span class = "socialcommerce_review_span_content"><?php echo $this->locale()->toNumber($this->moreInfos[$key]['quantity'])?></span></div>
        <div class = "socialcommerce_review_product">
            <span class ="socialcommerce_review_span_title"><?php echo $this->translate("Price")?></span>:<span class = "socialcommerce_review_span_content product_price_value"><?php echo $this->currency($this->moreInfos[$key]['total_amount'])?></span>
        </div>
    </div>
</div>
<?php endforeach; ?>

<h4><span><?php echo $this->translate("Order Summary");?></span></h4>
<ul>
    <li class = "ynstore_review_summary_li_class">
        <span class = "ynstore_review_span_summary_title"><?php echo $this->translate("Items").' ('.$this->locale()->toNumber($this->order->quantity).')'.':'?></span><span class = "ynstore_review_span_summary product_price_value"><?php echo $this->currency(round($this->order->total_amount - ($this->order->shipping_amount + $this->order->handling_amount),2))?></span>
    </li>
    <li class = "ynstore_review_summary_li_class">
        <span class = "ynstore_review_span_summary_title"><?php echo $this->translate("Shipping Fee").':'?></span><span class = "ynstore_review_span_summary product_price_value"><?php echo $this->currency(round(($this->order->shipping_amount + $this->order->handling_amount),2))?></span>
    </li>
    <hr class = "ynstore_review_summary_hr">
    <li class = "ynstore_review_summary_li_class">
        <span class = "ynstore_review_span_summary_title"><?php echo $this->translate("Order Total").':'?></span><span class = "ynstore_review_span_summary product_price_value"><?php echo $this->currency($this->order->total_amount)?></span>
    </li>
</ul>

<script type="text/javascript">
    window.addEvent('domready', function () {
        //for printing
        document.getElement('head').getElement('link[rel=stylesheet]').set('media', 'all');
        $('global_header').setStyle('display', 'none');
        $('global_footer').setStyle('display', 'none');
        window.print();
        setTimeout(function(){window.close();}, 2000);
    })
</script>