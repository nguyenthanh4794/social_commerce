
<?php
    echo $this->partial('_list_most_item.tpl', 'socialcommerce', array('stalls' => $this->paginator, 'tab' => 'stalls_browse_listing'));
?>
<?php if ($this->inBrowsePage): ?>
<a href="<?php echo $this->url(array('controller' => 'stall', 'action' => 'browse'), 'socialcommerce_general', true) ?>"><button><?php echo $this->translate('View more...') ?></button></a>
<?php endif; ?>

<?php if ($this->pager): ?>
<?php echo $this->paginationControl($this->paginator, null, null, array(
'pageAsQuery' => true,
'query' => $this->formValues,
)); ?>
<?php endif; ?>