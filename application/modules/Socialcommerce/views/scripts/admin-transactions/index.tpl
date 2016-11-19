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
    <?php echo $this->translate("SOCIALCOMMERCE_VIEWS_SCRIPTS_ADMINTRANSACTION_INDEX_DESCRIPTION") ?>
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
        <th style = "text-align: right;"><?php echo $this->translate("Trans ID") ?></th>
        <th style = "text-align: right;"><?php echo $this->translate("Order ID") ?></th>
        <th><?php echo $this->translate("Description") ?></th>
        <th style = "text-align: right;"><a href="javascript:void(0);" onclick="javascript:changeOrder('amount', 'DESC');"><?php echo $this->translate("Amount") ?></a></th>
        <th style = "text-align: right;"><?php echo $this->translate("Gateway Fee") ?></th>
        <th><a href="javascript:void(0);" onclick="javascript:changeOrder('payment_status', 'DESC');"><?php echo $this->translate("Status") ?></a></th>
        <th><?php echo $this->translate("Gateway") ?></th>
        <th style = "text-align: left;"><?php echo $this->translate("Buyer") ?></th>
        <th><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'DESC');"><?php echo $this->translate("Creation Date") ?></a></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->paginator as $item): ?>
    <tr>
        <td style = "text-align: left;">
            <?php echo $item->transaction_id ?>
        </td>

        <td style = "text-align: left;">
            <?php echo $item->order_id;?>
        </td>

        <td>
            <?php echo $item->transaction_type;?>
        </td>

        <td style = "text-align: right;">
            <?php echo round($item->amount, 2);?>
        </td>

        <td style = "text-align: right;">
            <?php echo $item->gateway_fee;?>
        </td>

        <td>
            <?php echo $this->translate($item->payment_status);?>
        </td>

        <td>
            <?php echo $item->gateway;?>
        </td>

        <td style = "text-align: left;">
            <?php if ($item->owner_id != 0) :?>
            <a href="<?php echo $this->user($item->owner_id)->getHref() ?>">
                <?php echo $this->user($item->owner_id)->getTitle() ?>
            </a>
            <?php else :?>
            <?php echo $this->translate('Guest');?>
            <?php endif;?>
        </td>

        <td>
            <?php

        date_default_timezone_set($this->viewer->timezone);
            echo date('Y-m-d H:i:s',strtotime($item->creation_date)); ?></td>


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
      <?php echo $this->translate("There are no transactions yet.") ?>
    </span>
</div>
<?php endif; ?>
