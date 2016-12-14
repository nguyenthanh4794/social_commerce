<?php if( count($this->navigation) > 0 ): ?>
<div>
    <?php
    // Render the menu
    echo $this->navigation()
    ->menu()
    ->setContainer($this->navigation)
    ->setPartial(array('_navIcons.tpl', 'socialcommerce'))
    ->render();
    ?>
</div>
<?php endif; ?>