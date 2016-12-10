<div class='socialcommerce_options_block'>
    <span class="socialcommerce_options_btn"><i class="fa fa-pencil"></i></span>
    <div class="socialcommerce_options">
        <?php
        echo $this->htmlLink(array(
        'route' => 'socialcommerce_general',
        'module' => 'socialcommerce',
        'controller' => 'stall',
        'action' => 'edit-info',
        'type' => $this->item -> getType(),
        'id' => $this->item->getIdentity(),
        ), '<i class="fa fa-pencil-square-o"></i>'.$this->translate('Edit stall'), array('class' => 'smoothbox icon_socialcommerce_edit'));
        ?>
        <?php
                    if ($item->status != 2) {
        echo $this->htmlLink(array(
        'route' => 'socialcommerce_general',
        'controller' => 'stall',
        'action' => 'delete',
        'stall_id' => $this->item->getIdentity(),
        'format' => 'smoothbox'
        ), '<i class="fa fa-trash"></i>'.$this->translate('Delete stall'), array('class' => 'smoothbox icon_socialcommerce_delete'));
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