<?php if (!$this->account):
$url = $this -> url(array(
'module' => 'socialcommerce',
'controller' => 'seller',
'action' => 'create',
'format' => 'smoothbox'),'default', true);?>

<div class="">
    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url?>')"><button><?php echo $this -> translate('Try to be trader!')?></button></a>
</div>
<?php else: ?>
<?php $url = $this -> url(array(
'module' => 'socialcommerce',
'controller' => 'seller',
'action' => 'edit',
'account_id' => $this->account->getIdentity(),
'format' => 'smoothbox'),'default', true);?>

<div class="">
    <a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url?>')"><button><?php echo $this -> translate('Edit Seller Information')?></button></a>
</div>

<h4><?php echo $this -> translate('Street Address'). ': ' ?></h4><p><?php echo $this->account->address ?></p>
<h4><?php echo $this -> translate('City'). ': ' ?></h4><p><?php echo $this->account->city ?></p>
<h4><?php echo $this -> translate('Country'). ': ' ?></h4><p><?php echo $this->account->country ?></p>
<h4><?php echo $this -> translate('ZIP / Postal Code'). ': ' ?></h4><p><?php echo $this->account->zip_code ?></p>
<h4><?php echo $this -> translate('Business Display Name'). ': ' ?></h4><p><?php echo $this->account->business_name ?></p>
<h4><?php echo $this -> translate('Website'). ': ' ?></h4><p><?php echo $this->account->web_address ?></p>
<h4><?php echo $this -> translate('Mobile Number'). ': ' ?></h4><p><?php echo $this->account->mobile ?></p>
<?php endif;?>
