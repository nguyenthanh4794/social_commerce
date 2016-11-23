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
    <?php echo $this->translate("SOCIALCOMMERCE_VIEWS_SCRIPTS_ADMINREQUEST_INDEX_DESCRIPTION") ?>
</p>
<br />
<?php if(count($this->paginator)): ?>
<table class="admin_table">
    <thead>
    <tr>
        <th style = "text-align: right;">
            <?php echo $this->translate("Amount") ?>
        </th>
        <th>
            <?php echo $this->translate("Seller") ?>
        </th>
        <th>
            <?php echo $this->translate("Store") ?>
        </th>
        <th>
            <?php echo $this->translate("Status") ?>
        </th>
        <th>
            <?php echo $this->translate("Request Date") ?>
        </th>
        <th>
            <?php echo $this->translate("Request Message") ?>
        </th>
        <th>
            <?php echo $this->translate("Response Date") ?>
        </th>
        <th>
            <?php echo $this->translate("Response Message") ?>
        </th>
        <th>
            <?php echo $this->translate("Options") ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($this->paginator as $item): ?>
    <tr>
        <td style = "text-align: right;">
            <?php echo $this->currency($item->request_amount) ?>
        </td>
        <td>
            <?php echo $item->getOwner() ?>
        </td>
        <td>
            <?php echo $item->getStall() ?>
        </td>
        <td>
            <?php echo $this->translate(ucfirst($item->request_status)) ?>
        </td>
        <td>
            <?php echo $item->request_date ?>
        </td>
        <td>
            <?php echo $item->request_message;?>
        </td>
        <td>
            <?php echo $item->response_date ?>
        </td>
        <td>
            <?php if ($item->response_message) {
            echo $item->response_message;
            }
            else {
            echo $this->translate('None');
            }
            ?>
        </td>
        <td>
            <?php if($item->isWaitingToProcess()): ?>
            <a href="<?php echo $this->url(array('action'=>'request-payment','id'=>$item->getIdentity(), 'status'=>1)) ?>"><?php echo $this->translate('Accept')?></a>|
            <a href="<?php echo $this->url(array('action'=>'request-payment','id'=>$item->getIdentity(),'status'=>0)) ?>"><?php echo $this->translate('Deny')?></a>
            <?php else: ?>
            <?php echo $this->translate('N/A')?>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<br/>
<!-- Page Changes  -->
<div>
    <?php  echo $this->paginationControl($this->paginator, null, null, array(
    'pageAsQuery' => false,
    'query' => $this->formValues,
    ));     ?>
</div>

<?php else:?>
<div class="tip">
    <span>
      <?php echo $this->translate("There are no requests yet.") ?>
    </span>
</div>

<?php endif; ?>
