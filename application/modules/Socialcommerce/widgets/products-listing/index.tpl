<?php if($this->inHomePage == true): ?>
<div class="" style="width: 230px">
    <ul class="action" id="ynsocialstore_category_menus">
    <?php foreach($this->categories as $aCategory): ?>
        <li class="ynstore-main-category-item">
            <a href="<?php echo $aCategory['link'] ?>">
                <span class=""><?php echo $aCategory['title'] ?></span>
                <span class="toggle fa fa-chevron-right"></span>
            </a>
            <?php if (!empty($aCategory['sub_categories'])): ?>
                <div style="display: none;" class="ynsocialstore_sub_category_items">
                    <ul>
                        <?php foreach($aCategory['sub_categories'] as $aSubCategory): ?>
                            <li class="main_sub_category_item">
                                <a href="<?php echo $aCategory['link']?>">
                                    <span class="ynmenu-text have-child"><?php echo $aSubCategory['title'] ?></span>
                                </a>
                                <?php if (!empty($aSubCategory['sub_categories'])): ?>
                                    <ul class="ynsocialstore_sub_sub_category_items">
                                        <?php foreach($aSubCategory['sub_categories'] as $aSubSubCategory): ?>
                                            <li class="main_sub_category_item">
                                                <a href="<?php echo $aCategory['link'] ?>">
                                                    <span class="ynmenu-text have-child"><?php echo $aSubSubCategory['title'] ?></span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
    <?php echo $this->paginationControl($this->paginator, null, null, array(
    'pageAsQuery' => true,
    'query' => $this->formValues,
    )); ?>
</div>
<script type="text/javascript">
    window.addEvent('domready', function () {
        $$('.ynstore-main-category-item').removeEvents().addEvents(
        {
            mouseover: function (ele) {
                this.addClass('active');
            },
            mouseout: function (ele) {
                this.removeClass('active');
            }
        });
    })
</script>
<?php endif; ?>
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

<script type="text/javascript">
    window.addEvent('domready', function() {
        jQuery.noConflict();
        ynSetModeView('<?php echo $this -> identity; ?>', '<?php echo $this -> view_mode; ?>');
    });
</script>