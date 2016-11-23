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
                        <?php if($order->order_status != 'completed'): ?>
                        <a href="<?php echo $this->url(array('controller'=>'account', 'action'=>'load-message', 'request_id'=>$order->getIdentity(), 'type'=>'request'), 'socialcommerce_account') ?>" class="smoothbox"><?php echo $this->translate('Received') ?></a> |
                        <?php endif; ?>
                        <a href="<?php echo $this->url(array('controller'=>'account', 'action'=>'load-message', 'request_id'=>$order->getIdentity(), 'type'=>'request'), 'socialcommerce_account') ?>" class="smoothbox"><?php echo $this->translate('Report') ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php echo $this->paginationControl($this->paginator); ?>
        </div>
    </div>
</div>