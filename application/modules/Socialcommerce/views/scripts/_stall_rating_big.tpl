<div class="yn-rating yn-rating-normal">
    <?php $rating = $this->stall->getRating(); ?>
    <?php for ($x = 1; $x <= 5; $x++): ?>
        <?php if ($x < $rating): ?>
            <i class="ynicon yn-star"></i>
        <?php elseif (($x - 1) < $rating && ($x - 1) > 0): ?>
            <i class="ynicon yn-star-half-o"></i>
        <?php else: ?>
            <i class="ynicon yn-star-o"></i>
        <?php endif; ?>
    <?php endfor; ?>
</div>