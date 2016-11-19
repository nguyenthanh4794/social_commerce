<form method="post" class="global_form_popup" action="<?php echo $this->url(); ?>">
    <div>
        <h3><?php echo $this->translate("Delete All Selected Listings?") ?></h3>
        <p>
            <?php echo $this->translate("Are you sure that you want to delete all selected listings? They will not be recoverable after being deleted.") ?>
        </p>
        <br />
        <p>
            <button type='submit'><?php echo $this->translate("Delete") ?></button>
            <?php echo $this->translate("or") ?>
            <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
                <?php echo $this->translate("cancel") ?>
            </a>
        </p>
    </div>
</form>

<?php if( @$this->closeSmoothbox ): ?>
<script type="text/javascript">
    parent.submitForm();
    parent.Smoothbox.close();
</script>
<?php endif; ?>