<?php if( $this->subject()->cover_id != 0 ): ?>
    <div>
        <img src="<?php echo $this->subject()->getCoverPhotoUrl() ?>" alt="" id="lassoImg" class="thumb_profile item_cover_photo_preview  thumb_profile">
    </div>
    <br />

<script type="text/javascript">
    var uploadSignupPhoto = function() {
        $('thumbnail-controller').innerHTML = "<div><img class='loading_icon' src='application/modules/Core/externals/images/loading.gif'/><?php echo $this->translate('Loading...')?></div>";
        $('EditStallPhoto').submit();
        $('Filedata-wrapper').innerHTML = "";
    }
</script>
<?php else: ?>
<div>
    <img src="<?php echo $this->layout()->staticBaseUrl . 'application/modules/Socialcommerce/externals/images/socialcommerce_default_cover.jpg' ?>" alt="" id="lassoImg" class="item_cover_photo_preview ">
</div>
<br />
<?php endif; ?>