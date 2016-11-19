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

<p class="description">
    <?php echo $this->translate("SOCIALCOMMERCE_VIEWS_SCRIPTS_ADMINACCOUNT_INDEX_DESCRIPTION") ?>
</p>

<?php if( count($this->paginator) ): ?>
<table class='admin_table' style="width: 100%">
    <thead>
    <tr>
        <th><?php echo $this->translate("User ID") ?></th>
        <th><?php echo $this->translate("User Account") ?></th>
        <th ><?php echo $this->translate("Payment Account") ?></th>
        <th><?php echo $this->translate("Total Amount") ?></th>
        <th><?php echo $this->translate("Currency") ?></th>
        <th><?php echo $this->translate("Creation Date") ?></th>
        <th><?php echo $this->translate("Options") ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->paginator as $item): ?>
    <tr>
        <td>
            <?php echo $item->user_id ?>
        </td>

        <td>
            <?php echo $item->getOwner() ?>
        </td>

        <td>
            <?php echo $item->account_username ?>
        </td>

        <td>
            <?php echo $item->total_amount ?>
        </td>

        <td>
            <?php echo Engine_Api::_() -> getApi('settings', 'core')->getSetting('payment.currency', 'USD') ?>
        </td>

        <td>
            <?php date_default_timezone_set($this->viewer->timezone);
            echo date('Y-m-d H:i:s',strtotime($item->creation_date)); ?>
        </td>

        <td>

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
      <?php echo $this->translate("There are no accounts yet.") ?>
    </span>
</div>
<?php endif; ?>