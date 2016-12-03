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
                <?php foreach($this->paginator as $order): ?>
                <tr>
                    <td><?php echo $order->order_id ?></td>
                    <?php $product = $order->getObject(); ?>
                    <td>
                        <div class="order_product_item">
                            <div class="order_product_image">
                                <?php echo $this->htmlLink($product->getHref(), $this->itemPhoto($product, 'thumb.icon'), array('class' => 'thumb')) ?>
                            </div>
                            <div class="order_product_title">
                                <?php echo $product->getTitle(); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php
                  $oDate = new DateTime($order->creation_date);
                        $oDate->setTimezone(new DateTimeZone($this->viewer->timezone));
                        echo $oDate->format("H:ia d/m/Y")
                        ?>
                    </td>
                    <td><?php echo $order->	quantity ?></td>
                    <td><?php echo $order->	total_amount ?></td>
                    <td><?php echo $order->	order_status ?></td>
                    <td>
                        <?php if($order->order_status != 'deliveried'): ?>
                        <a href="<?php echo $this->url(array('controller'=>'seller', 'action'=>'received', 'order_id' => $order->order_id), 'socialcommerce_general') ?>" class="smoothbox"><?php echo $this->translate('Received') ?></a> |
                        <?php echo $this->htmlLink(Array('module'=> 'core', 'controller' => 'report', 'action' => 'create', 'route' => 'default', 'subject' => $product->getGuid(), 'format' => 'smoothbox'), '<i class="ynicon yn-warning-triangle"></i>'.$this->translate("Report this Product"), array('class' => 'smoothbox')); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php echo $this->paginationControl($this->paginator); ?>
        </div>
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