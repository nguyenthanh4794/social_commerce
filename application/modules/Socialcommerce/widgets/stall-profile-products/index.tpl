<div class="socialcommerce-profile-module-header">
    <div class="socialcommerce-profile-header-right">
        <?php if ($this->viewer() -> getIdentity()):
            $url = $this -> url(array(
            'module' => 'socialcommerce',
            'controller' => 'stall',
            'action' => 'add-product',
            'type' => $this->stall -> getType(),
            'id' => $this->stall -> getIdentity(),
            'format' => 'smoothbox'),'default', true);?>
        
            <a href="javascript:void(0);" class="buttonlink" onclick="checkOpenPopup('<?php echo $url?>')"><?php echo $this -> translate('+ Add Product')?></a>
        <?php endif;?>
    </div>
</div>


<div id="socialcommerce_list_item_browse_content" class="socialcommerce-tabs-content">
    <div id="tab_products_browse_products">
        <?php
            echo $this->partial('_product_listing.tpl', 'socialcommerce', array('products' => $this->paginator));
        ?>
    </div>
</div>
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