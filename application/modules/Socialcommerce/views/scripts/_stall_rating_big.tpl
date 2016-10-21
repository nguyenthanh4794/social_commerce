<div class="stalls_block">
    <?php $rating = $this->stall->getRating(); ?>
    <?php for ($x = 1; $x <= 5; $x++): ?>
        <?php if ($x < $rating): ?>
            <span class="ynicon yn-star"></span>
        <?php elseif (($x - 1) < $rating && ($x - 1) > 0): ?>
            <span class="ynicon yn-star-half-o"></span>
        <?php else: ?>
            <span class="ynicon yn-star-o"></span>
        <?php endif; ?>
    <?php endfor; ?>
</div>