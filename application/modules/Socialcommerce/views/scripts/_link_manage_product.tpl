<div class='socialcommerce_options_block'>
    <span class="socialcommerce_options_btn"><i class="fa fa-pencil"></i></span>
    <div class="socialcommerce_options">
        <?php
        echo $this->htmlLink(array(
        'route' => 'socialcommerce_specific',
        'action' => 'edit',
        'id' => $this->item->getIdentity(),
        ), '<i class="fa fa-pencil-square-o"></i>'.$this->translate('Edit listing'), array('class' => 'icon_socialcommerce_edit'));
        ?>

        <?php
        echo $this->htmlLink(array(
        'route' => 'socialcommerce_general',
        'controller' => 'photo',
        'action' => 'manage',
        'listing_id' => $this->item->getIdentity(),
        ), '<i class="fa fa-picture-o"></i>'.$this->translate('Manage photos'), array('class' => 'icon_socialcommerce_edit'));
        ?>

        <?php
                    if ($item->status != 2) {
        echo $this->htmlLink(array(
        'route' => 'socialcommerce_general',
        'controller' => 'product',
        'action' => 'delete',
        'listing_id' => $this->item->getIdentity(),
        'format' => 'smoothbox'
        ), '<i class="fa fa-trash"></i>'.$this->translate('Delete listing'), array('class' => 'smoothbox icon_socialcommerce_delete'));
        }
        ?>
    </div>
</div>
<script type="text/javascript">
    $$('.socialcommerce_options_btn').removeEvents().addEvent('click', function () {
        var ele = $(this).getSiblings('.socialcommerce_options');
        ele.toggle();
    });
</script>