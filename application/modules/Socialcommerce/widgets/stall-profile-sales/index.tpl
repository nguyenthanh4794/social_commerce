<div class="socialcommerce_my_account">
    <div>
        <div class="socialcommerce_manage_requests_table_parent">
            <table class="socialcommerce_manage_requests_table">
        <thead>
            <tr>
                <th><?php echo $this->translate('OrderID') ?></th>
                <th><?php echo $this->translate('Product') ?></th>
                <th><?php echo $this->translate('Quantity') ?></th>
                <th><?php echo $this->translate('Buyer') ?></th>
                <th><?php echo $this->translate('Bought on') ?></th>
                <th><?php echo $this->translate('Shipping Info') ?></th>
                <th><?php echo $this->translate('Status') ?></th>
                <th><?php echo $this->translate('Action') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($this->paginator as $order): ?>
        <tr>
            <td><?php echo $order->order_id ?></td>
            <?php $product = $order->getObject(); ?>
            <td>
                <a href="<?php echo $product->getHref() ?>"><?php echo $product->getTitle(); ?></a>
            </td>
            <td><?php echo $order->quantity ?></td>
            <td><?php echo $order->getBuyer()?></td>
            <td>
                <?php
                  $oDate = new DateTime($order->creation_date);
                $oDate->setTimezone(new DateTimeZone($this->viewer->timezone));
                echo $oDate->format("H:ia d/m/Y")
                ?>
            </td>

            <td  class="big"><?php echo $order->	getShippingAddressInfo() ?></td>
            <td><?php echo $order->	order_status ?></td>
            <td>
                <?php if($order->order_status == 'initial'): ?>
                <a href="<?php echo $this->url(array('controller'=>'seller', 'action'=>'shipped', 'order_id' => $order->order_id), 'socialcommerce_general') ?>" class="smoothbox"><?php echo $this->translate('Shipped') ?></a> |
                <?php endif; ?>
                <a target="_blank" href="<?php echo $this->url(array('controller'=>'seller', 'action'=>'print', 'order_id' => $order->order_id), 'socialcommerce_general') ?>"><?php echo $this->translate('Print') ?></a> |
                <?php echo $this->htmlLink(Array('module'=> 'core', 'controller' => 'report', 'action' => 'create', 'route' => 'default', 'subject' => $product->getGuid(), 'format' => 'smoothbox'), '<i class="ynicon yn-warning-triangle"></i>'.$this->translate("Report this Product"), array('class' => 'smoothbox')); ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
        </div>
    </div>
</div>
