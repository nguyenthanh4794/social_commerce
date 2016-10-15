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

    <div id="socialcommerce_list_item_browse_content" class="socialcommerce-tabs-content ynclearfix">
        <div id="tab_stalls_browse_stalls">
            <?php
			echo $this->partial('_list_most_item.tpl', 'socialcommerce', array('stalls' => $this->paginator, 'tab' => 'stalls_browse_listing'));
            ?>
        </div>
        <iframe id='browse-iframe' style="max-height: 500px;"> </iframe>
    </div>
</div>

<script type="text/javascript">
    window.addEvent('domready', function() {
        ynSetModeView('<?php echo $this -> identity; ?>', '<?php echo $this -> view_mode; ?>');
    });
</script>