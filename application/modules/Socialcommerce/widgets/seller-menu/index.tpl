<div class="">
    <?php // This is rendered by application/modules/core/views/scripts/_navIcons.tpl
    echo $this->navigation()
    ->menu()
    ->setContainer($this->navigation)
    ->setPartial(array('_navIcons.tpl', 'socialcommerce'))
    ->render()
    ?>
</div>