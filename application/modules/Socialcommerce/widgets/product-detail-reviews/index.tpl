<?php if(count($this->reviews) > 0): ?>
<?php if ($this->my_review): ?>
<div class="socialcommerce-product-add-review">
    <div class="socialcommerce-rating-holder">
        <div id="socialcommerce-rating-result">
            <div class="socialcommerce-title">
                <?php echo $this->translate('Thank You for Your Review'); ?>
            </div>
            <div class="yn-rating yn-rating-big">
                <?php for ($i = 1 ; $i <= 5; $i++): ?>
                    <?php if ($i <= $this->my_review->rate_number): ?>
                        <i class='ynicon yn-star'></i>
                    <?php else: ?>
                        <i class='ynicon yn-star-o'></i>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="comments">
    <?php foreach($this->reviews as $review): ?>
        <ul>
            <li>
                <div class="comments_author_photo">
                    <?php echo $this->htmlLink($review->getOwner()->getHref(), $this->itemPhoto($review->getOwner(), 'thumb.icon')) ?>
                </div>
                <div class="comments_info">
                    <span class="comments_author">
                        <?php echo $review->getOwner(); ?>
                    </span>
                    <div class="yn-rating yn-rating-small">
                        <span><?php echo $review -> rate_number.'/5'; ?></span>
                        <?php for ($i = 1 ; $i <= 5; $i++): ?>
                        <?php if ($i <= $review -> rate_number): ?>
                        <i class='ynicon yn-star'></i>
                        <?php else: ?>
                        <i class='ynicon yn-star-o'></i>
                        <?php endif; ?>
                        <?php endfor; ?>
                        <span class="comments_date"><?php echo $this->timestamp($review->creation_date); ?></span>
                    </div>
                    <span class="comments_body"><?php echo $this->viewMore(nl2br($review->body)); ?></span>
                </div>
            </li>
        </ul>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="tip">
    <span><?php echo $this->translate('There are no reviews.'); ?></span>
</div>
<?php endif; ?>
<style>
    .socialcommerce-product-add-review {
        margin-top: 20px;
        margin-bottom: 20px;
        border: 1px solid #d9d9d9;
    }
    .socialcommerce-rating-holder {
        background-color: #fff;
        padding: 20px;
    }
    #socialcommerce-rating-result {
        margin: -5px 0px -10px -5px;
    }
    .socialcommerce-title {
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 16px;
        color: #5cb85c;
        padding-bottom: 0;
        border-bottom: 0;
    }
</style>