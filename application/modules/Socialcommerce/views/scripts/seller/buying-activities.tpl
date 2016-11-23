<div class="generic_layout_container layout_top">
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
</div>

<div class="generic_layout_container layout_main">
    <div class="layout_right">
        <!-- render mini menu -->
        <?php echo $this->content()->renderWidget('socialcommerce.seller-menu') ?>
    </div>

    <div class="layout_middle">
        <div class="socialcommerce_my_account_request_search">
            <?php echo $this->form->render($this) ?>
        </div>
    </div>
</div>