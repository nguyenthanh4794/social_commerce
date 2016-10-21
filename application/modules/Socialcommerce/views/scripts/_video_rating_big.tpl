<?php

/**
 * YouNet Company
 *
 * @category   Application_Extensions
 * @package    Ynvideo
 * @author     YouNet Company
 */
?>
<div class="yn-rating yn-rating-small">
    <?php for ($x = 1; $x <= $this->video->rating; $x++): ?>
        <i class="ynicon yn-star"></i>
    <?php endfor; ?>
    <?php if ((round($this->video->rating) - $this->video->rating) > 0): $x ++; ?>
        <i class="ynicon yn-star-half-o"></i>
    <?php endif; ?>
    <?php if ($x <= 5) :?>
        <?php for (; $x <= 5; $x++ ) : ?>
            <i class="ynicon yn-rating-disable"></i>
        <?php endfor; ?>
    <?php endif; ?>
    
</div>