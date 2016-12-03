<div class="socialcommerce-view-modes-block yn-viewmode-list">
    <ul class="socialcommerce-items">
        <?php foreach($this->paginator as $product): ?>
            <?php echo $this->partial('_entry_product.tpl', 'socialcommerce', array('item' => $product)); ?>
        <?php endforeach; ?>
    </ul>
</div>
