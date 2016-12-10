<div class="">
    <ul class="action" id="ynsocialstore_category_menus">
        <?php foreach($this->categories as $aCategory): ?>
        <li class="ynstore-main-category-item">
            <a href="<?php echo $aCategory['link'] ?>">
                <span class=""><?php echo $aCategory['title'] ?></span>
                <span class="toggle fa fa-chevron-right"></span>
            </a>
        </li>
        <?php 
        $doc = new DOMDocument(); 
        $doc->loadHTMLFile('index.tpl');    
        $elem = $doc->getElementById('sub_categories_list'); 
        echo $elem;
        ?>

        <?php endforeach; ?>
    </ul>
    <div class="ynsocialstore_sub_category_items">
        <ul style="display:none;" id="sub_categories_list">
            <li><span>lorem ifsum</span></li>
            <li><span>lorem ifsum</span></li>
            <li><span>lorem ifsum</span></li>
        </ul> 
    </div>
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
