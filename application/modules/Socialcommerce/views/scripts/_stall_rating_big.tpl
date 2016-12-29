<div class="yn-rating yn-rating-normal">
    <?php $rating = $this->stall->getRating(); ?>
    <?php for ($x = 0; $x < 5; $x++): ?>
        <?php if ($x < (int)$rating): ?>
            <i class="ynicon yn-star"></i>
        <?php elseif ((($rating - round($rating)) > 0) && ($rating - $x) > 0): ?>
            <i class="ynicon yn-star-half-o"></i>
        <?php else: ?>
            <i class="ynicon yn-star-o"></i>
        <?php endif; ?>
    <?php endfor; ?>
</div>