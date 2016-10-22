<?php if ($this->viewer() -> getIdentity()):
$url = $this -> url(array(
'module' => 'socialcommerce',
'controller' => 'stall',
'action' => 'add-product',
'type' => $this->stall -> getType(),
'id' => $this->stall -> getIdentity(),
'format' => 'smoothbox'),'default', true);?>

<div class="">
    <a href="javascript:void(0);" onclick="checkOpenPopup('<?php echo $url?>')"><i class="ynicon yn-plus-thin" title="<?php echo $this -> translate('Add Product')?>"></i></a>
</div>
<?php endif;?>


<script type="text/javascript">
    function checkOpenPopup(url)
    {
        if(window.innerWidth <= 480)
        {
            Smoothbox.open(url, {autoResize : true, width: 300});
        }
        else
        {
            Smoothbox.open(url);
        }
    }
</script>