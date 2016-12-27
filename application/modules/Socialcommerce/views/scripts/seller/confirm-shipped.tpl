<form method="post" class="global_form_popup" action="<?php echo $this->url(array()) ?>">
    <div>
        <h3><?php echo $this->translate("Shipping Confirm") ?></h3>
        <p>
            <?php echo $this->translate("Do you want to confirm that you have shipped this the parcel to the Buyer?") ?>
        </p>
        <br />
        <p>
            <input type="hidden" name="order_id" value="<?php echo $this->order_id?>"/>
            <button type='submit'><?php echo $this->translate("Confirm") ?></button>
            <?php echo $this->translate("or") ?>
            <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
                <?php echo $this->translate("cancel") ?>
            </a>
        </p>
    </div>
</form>

<?php if( @$this->closeSmoothbox ): ?>
<script type="text/javascript">
    TB_close();
</script>
<?php endif; ?>