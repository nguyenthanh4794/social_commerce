<h2><?php echo $this->translate("Social Commerce Plugin") ?></h2>
<?php if( count($this->navigation) ): ?>
<div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
</div>
<?php endif; ?>

<p>
    <?php echo $this->translate("SOCIALCOMMERCE_VIEWS_SCRIPTS_ADMINORDER_INDEX_DESCRIPTION") ?>
</p>

<br />
<div class='admin_search'>
    <?php  echo $this->form->render($this); ?>
</div>
<?php //echo $this->count." ".$this->translate('order(s)');   ?>
<br/>
<?php if( count($this->paginator) ): ?>
<script type="text/javascript">
    var currentOrder = '<?php echo $this->formValues['order'] ?>';
    var currentOrderDirection = '<?php echo $this->formValues['direction'] ?>';
    var changeOrder = function(order, default_direction){
        // Just change direction
        if( order == currentOrder ) {
            $('direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
        } else {
            $('order').value = order;
            $('direction').value = default_direction;
        }
        $('filter_form').submit();
    }
</script>
<table class='admin_table'>
    <thead>
    <tr>
        <th><?php echo $this->translate("Order ID") ?></th>
        <th><?php echo $this->translate("Buyer") ?></th>
        <th ><?php echo $this->translate("Status") ?></th>
        <th><?php echo $this->translate("Currency") ?></th>
        <th><a href="javascript:void(0);" onclick="javascript:changeOrder('quantity', 'DESC');"><?php echo $this->translate("Quantity") ?></a></th>
        <th><a href="javascript:void(0);" onclick="javascript:changeOrder('sub_amount', 'DESC');"><?php echo $this->translate("Sub Amount") ?></a></th>
        <th><a href="javascript:void(0);" onclick="javascript:changeOrder('total_amount', 'DESC');"><?php echo $this->translate("Total Amount") ?></a></th>
        <th><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'DESC');"><?php echo $this->translate("Order Date") ?></a></th>
        <th><?php echo $this->translate("Options") ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->paginator as $item): ?>
    <tr>
        <td>
            <?php echo $item->order_id ?>
        </td>

        <td>
            <?php if ($item->owner_id != 0) :?>
            <a href="<?php echo $this->user($item->owner_id)->getHref() ?>">
                <?php echo $this->user($item->owner_id)->getTitle() ?>
            </a>
            <?php else :?>
            <?php echo $this->translate('Guest');?>
            <?php endif;?>
        </td>

        <td>
            <?php echo $this->translate($item->payment_status);?>
        </td>
        <td>
            <?php echo Engine_Api::_() -> getApi('settings', 'core')->getSetting('payment.currency', 'USD')?>
        </td>
        <td>
            <?php echo $item->quantity;?>
        </td>
        <td>
            <?php echo $this->currency($item->getSubAmount());?>
        </td>
        <td>
            <?php echo $this->currency($item->getTotalAmount());?>
        </td>
        <td>
            <?php date_default_timezone_set($this->viewer->timezone);
            echo date('Y-m-d H:i:s',strtotime($item->creation_date)); ?></td>

        <td>
            <?php
          echo $this->htmlLink(array(
            'route' => 'admin_default',
            'module' => 'socialcommerce',
            'controller' => 'orders',
            'action' => 'order-detail',
            'order_id' => $item->order_id,
            ), $this->translate('view details'), array(
            ))
            ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<br />
<div>
    <?php  echo $this->paginationControl($this->paginator, null, null, array(
    'pageAsQuery' => false,
    'query' => $this->formValues,
    ));     ?>
</div>

<?php else:?>
<div class="tip">
    <span>
      <?php echo $this->translate("There are no orders yet.") ?>
    </span>
</div>
<?php endif; ?>