<?php if( count($this->navigation) > 0 ): ?>
<div class="quicklinks">
    <?php
    // Render the menu
    echo $this->navigation()
    ->menu()
    ->setContainer($this->navigation)
    ->render();
    ?>
</div>
<?php endif; ?>