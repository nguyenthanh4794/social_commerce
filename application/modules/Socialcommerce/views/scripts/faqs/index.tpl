<div class="headline">
    <h2>
        <?php echo $this->translate('Socialcommerce');?>
    </h2>
    <div class="quicklinks">
        <?php
          // Render the menu
          echo $this->navigation()
        ->menu()
        ->setContainer($this->navigation)
        ->render();
        ?>
    </div>
</div>

<div class="layout_middle">
    <div class="socialcommerce_title"><?php echo $this->translate("FAQs") ?></div>

    <?php if (count($this->items) > 0): ?>

    <div class="socialcommerce_faq">
        <?php $i=0; foreach($this->items as $item): ?>
        <div class="socialcommerce_faq_items">
            <div class="socialcommerce_faq_question">
                <span class="ynicon yn-question-circle yn-float-left"></span>
                <span class="ynicon yn-arr-down yn-float-right"></span>
                <span class="socialcommerce_faq_question_text"><?php echo $item->question ?></span>
            </div>
            <div class="socialcommerce_faq_answer">
                <div class="socialcommerce_faq_answer_content">
                    <?php echo $item->answer ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php else: ?>
<div class="tip"><span><?php echo $this->translate("There are no FAQ items created.") ?></span></div>
<?php endif; ?>


<script type="text/javascript">
    window.addEvent('domready', function(){

        $$('.socialcommerce_faq_items').removeEvent('click').addEvent('click',function(){
            this.toggleClass('socialcommerce_faq_show');
            var child = this.getChildren('.socialcommerce_faq_answer > .socialcommerce_faq_answer_content')[0];
            if (this.hasClass('socialcommerce_faq_show')) {
                this.getChildren('.socialcommerce_faq_answer')[0].setStyle('height',child.getHeight() + 'px')
            } else {
                this.getChildren('.socialcommerce_faq_answer')[0].setStyle('height',0)
            }


        });
    });
</script>