<div class="quicklinks">
    <?php // This is rendered by application/modules/core/views/scripts/_navIcons.tpl
    echo $this->navigation()
    ->menu()
    ->setContainer($this->navigation)
    ->setPartial(array('_navIcons.tpl', 'core'))
    ->render()
    ?>
</div>