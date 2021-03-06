<?php
      $baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()->appendStylesheet($baseUrl . 'application/modules/Socialcommerce/externals/styles/ui-redmond/jquery-ui-1.8.18.custom.css');

$this->headScript()
->appendFile($baseUrl .'application/modules/Socialcommerce/externals/scripts/jquery-1.10.2.min.js')
->appendFile($baseUrl .'application/modules/Socialcommerce/externals/scripts/jquery-ui-1.11.4.min.js')

?>


<div class="socialcommerce_my_account">
    <div>
        <div class="socialcommerce_my_account_request_block">
            <div class="socialcommerce_my_account_request_search">
                <?php echo $this->form->render($this) ?>
            </div>
        </div>
        <?php if(count($this->paginator) > 0): ?>
        <div class="socialcommerce_manage_requests_table_parent">
            <table class="socialcommerce_manage_requests_table">
                <thead>
                <tr>
                    <th><?php echo $this->translate('OrderID') ?></th>
                    <th><?php echo $this->translate('Product') ?></th>
                    <th><?php echo $this->translate('Bought on') ?></th>
                    <th><?php echo $this->translate('Quantity') ?></th>
                    <th><?php echo $this->translate('Total') ?></th>
                    <th><?php echo $this->translate('Status') ?></th>
                    <th><?php echo $this->translate('Confirmation') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($this->paginator as $orderItem): ?>
                <tr>
                    <td><a href="<?php echo $this->url(array('module' => 'socialcommerce', 'controller' => 'order', 'action' => 'detail', 'order_id' => $orderItem->order_id), 'default'); ?>"><?php echo $orderItem->order_id ?></td>
                    <?php $product = $orderItem->getObject(); ?>
                    <td>
                        <?php echo $this->htmlLink($product->getHref(), $this->itemPhoto($product, 'thumb.normal').'<span>'.$product->getTitle().'</span>', array('class' => 'thumb_icon')) ?>
                    </td>
                    <td>
                        <?php
                  $oDate = new DateTime($orderItem->creation_date);
                        $oDate->setTimezone(new DateTimeZone($this->viewer->timezone));
                        echo $oDate->format("H:ia d/m/Y")
                        ?>
                    </td>
                    <td><?php echo $orderItem->	quantity ?></td>
                    <td><?php echo $orderItem->	total_amount ?></td>
                    <td><?php echo $orderItem->	delivery_status ?></td>
                    <td>
                        <?php if($orderItem->delivery_status != 'delivered'): ?>
                        <a href="<?php echo $this->url(array('controller'=>'seller', 'action'=>'received', 'orderItem_id' => $orderItem->getIdentity()), 'socialcommerce_general') ?>" class="smoothbox"><?php echo $this->translate('Received') ?></a> |
                        <?php endif; ?>
                        <?php echo $this->htmlLink(Array('module'=> 'core', 'controller' => 'report', 'action' => 'create', 'route' => 'default', 'subject' => $product->getGuid(), 'format' => 'smoothbox'), '<i class="ynicon yn-warning-triangle"></i>'.$this->translate("Report this Product"), array('class' => 'smoothbox')); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php echo $this->paginationControl($this->paginator); ?>
        </div>
        <?php else: ?>
        <div class="tip">
            <span><?php echo $this->translate('There are no orders found.'); ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>


<script>
    jQuery(document).ready(function(){
        jQuery('#start_date').datepicker({
            firstDay: 1,
            showOn: "button",
            buttonImageOnly: true,
            buttonText: '<?php echo $this -> translate("Select date")?>',
            dateFormat: 'yy-mm-dd'
        });

        jQuery('#to_date').datepicker({
            firstDay: 1,
            showOn: "button",
            buttonImageOnly: true,
            buttonText: '<?php echo $this -> translate("Select date")?>',
            dateFormat: 'yy-mm-dd'
        });
    });
</script>