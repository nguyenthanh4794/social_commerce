<!-- render my widget -->
<div class="center">
	<img width="120" height="120" src="http://www.nhsdirect.wales.nhs.uk/assets/images/tick.png">
	<h2>
		<?php echo $this->translate('Your purchase has been completed.');?>
	</h2>
	<p>
		<?php
		$url = $this->order->getPlugin()->getSuccessRedirectUrl($this->order->order_id);
		?>
		<?php echo $this->translate('Click %1$shere%2$s to view to your order', '<a href="'.$url.'">','</a>') ?>
	</p>
</div>


