<?php if ($this->viewer() -> getIdentity()):
$url = $this -> url(array(
'module' => 'socialcommerce',
'controller' => 'stall',
'action' => 'add-product',
'type' => $this->stall -> getType(),
'id' => $this->stall -> getIdentity(),
'format' => 'smoothbox'),'default', true);?>

<div class="">
    <a href="javascript:void(0);" onclick="checkOpenPopup('<?php echo $url?>')"><button><?php echo $this -> translate('Add Product')?></button></a>
</div>
<?php endif;?>

<div id="yn-view-modes-block-<?php echo $this -> identity; ?>">
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

<script type="text/javascript">
    window.addEvent('domready', function() {
        ynSetModeView('<?php echo $this -> identity; ?>', '<?php echo $this -> view_mode; ?>');
    });
</script>
<script type="text/javascript">
    function checkOpenPopup(url)
    {
        if(window.innerWidth <= 480)
        {
            Smoothbox.open(url, {autoResize : true, width: 300});
        }
        else
        {
            Smoothbox.open(url);
        }
    }
</script>