<div class="">
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
