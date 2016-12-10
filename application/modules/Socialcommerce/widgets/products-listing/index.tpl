<div id="yn-view-modes-block-<?php echo $this -> identity; ?>" class="yn-viewmode-simple">
    <div id="socialcommerce_list_item_browse_content" class="socialcommerce-tabs-content">
        <div id="tab_products_browse_products">
            <?php
                echo $this->partial('_product_listing.tpl', 'socialcommerce', array('products' => $this->paginator));
            ?>
        </div>
    </div>
</div>