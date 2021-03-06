<div id="yn-view-modes-block-<?php echo $this -> identity; ?>" class="yn-viewmode-simple">
    <div class="yn-view-modes clearfix">
        <span data-mode="simple" class="yn-view-mode" title="<?php echo $this->translate('Grid View')?>">
            <i class="ynicon yn-grid-view"></i>
        </span>
        <span data-mode="list" class="yn-view-mode" title="<?php echo $this->translate('List View')?>">
            <i class="ynicon yn-list-view"></i>
        </span>
        <span data-mode="casual" class="yn-view-mode" title="<?php echo $this->translate('Casual View')?>">
            <i class="ynicon yn-casual-view"></i>
        </span>
    </div>

    <div id="socialcommerce_list_item_browse_content" class="socialcommerce-tabs-content">
        <div id="tab_products_browse_products">
            <?php
                echo $this->partial('_product_listing.tpl', 'socialcommerce', array('products' => $this->paginator));
            ?>
        </div>
    </div>
</div>

<?php if ($this->inBrowsePage): ?>
<a href="<?php echo $this->url(array('controller' => 'product', 'action' => 'browse'), 'socialcommerce_general', true) ?>"><button><?php echo $this->translate('View more...') ?></button></a>
<?php endif; ?>

<?php if($this->pager): ?>
<?php echo $this->paginationControl($this->paginator, null, null, array(
'pageAsQuery' => true,
'query' => $this->formValues,
)); ?>
<?php endif; ?>

<script type="text/javascript">
    window.addEvent('domready', function() {
        jQuery.noConflict();
        ynSetModeView('<?php echo $this -> identity; ?>', '<?php echo $this -> view_mode; ?>');
    });
</script>