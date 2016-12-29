<!-- render my widget -->
<div class="center">
    <img src="http://www.procliparts.com/resize/900w/cliparts/pele/UqBi89iB5-circle-x.png" width="120" height="120" alt="">
    <h2><?php echo $this->translate('Error! Something went Wrong!');?></h2>
    <p class="description">
        <?php
	if ($this->response != null) {
        echo $this->response->getMessage();
        } ?>
    </p>
    <br />
    <p>
        <?php

$url = $this->order->getPlugin()->getSuccessRedirectUrl();

        echo $this->translate('Click %1$shere%2$s to redirect to your shopping cart', '<a href="'.$url.'">','</a>') ?>
    </p>
</div>
