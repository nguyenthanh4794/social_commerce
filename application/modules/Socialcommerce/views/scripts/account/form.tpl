<div class="global_form_popup">
	<h3><?php echo $this->translate('Create request')?></h3>
	<div>
		<div>
			<div class="socialcommerce_account_request_label"><?php echo $this->translate('Minimum amount')?></div>
			<div class="socialcommerce_account_request_number">
				<?php echo $this->locale()->toCurrency($this->min_payout, $this->currency) ?>
				<?php echo $this->translate('(%s)', $this->currency) ?>
			</div>
		</div>
		<div>
			<div class="socialcommerce_account_request_label"><?php echo $this->translate('Maximum amount')?></div>
			<div class="socialcommerce_account_request_number">
				<?php echo $this->locale()->toCurrency($this->max_payout, $this->currency) ?>
				<?php echo $this->translate('(%s)', $this->currency) ?>
			</div>
		</div>
	</div>
<?php echo $this->form->render($this) ?>
</div>


<?php if( @$this->closeSmoothbox ): ?>
<script type="text/javascript">
  TB_close();
</script>
<?php endif; ?>
