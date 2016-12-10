<link rel="stylesheet" href="<?php echo $this->baseUrl()?>/application/modules/Socialcommerce/externals/styles/aleofont.css" />
<?php if(count($this->stalls) > 0):?>
<ul class="generic_list_widget stall_browse">
    <?php foreach( $this->stalls as $stall ): ?>
    <li>
        <div class="socialcommerce-list-item">
            <div class="list_photo_product">
                <?php $photo_url = ($stall->getCoverPhotoUrl('thumb.profile')) ? $stall->getCoverPhotoUrl('thumb.profile') : "";?>
                <div class="stall_photo stall_photo_main" style="background-image: url(<?php echo $photo_url; ?>);">
                    <div style="margin-top: 100%;">
                    </div>
                </div>
                <div class="stall_photo pu_product_in_photo" style="background-image: url(<?php echo $photo_url; ?>);">
                    <div style="margin-top: 100%;">
                    </div>
                </div>
                <div class="stall_photo pu_product_in_photo" style="background-image: url(<?php echo $photo_url; ?>);">
                    <div style="margin-top: 100%;">
                    </div>
                </div>
                <div class="stall_photo pu_product_in_photo" style="background-image: url(<?php echo $photo_url; ?>);">
                    <div style="margin-top: 100%;">
                    </div>
                </div>
            </div>
            <div class="stall_description">
                <div class="stall_name">
                    <h3 class="socialcommerce_stall_profile_name">
                        <?php echo $this->translate($stall->title) ?>
                    </h3>
                </div>
                <div class="stall_rating">
                    <span><?php
                        echo $this->partial('_stall_rating_big.tpl', 'socialcommerce', array('stall' => $stall));
                        ?>
                    </span>
                </div>
                <div class="stall_info">
                    <span class="stall_info_peace">30 items &nbsp;|&nbsp;</span>
                    <span class="stall_info_peace">by GladYolus</span>
                    <span class="stall_info_peace final_peace">
                        <a href="<?php echo $stall->getHref(); ?>" class="alink float_right right_padding">Shop now</a>
                    </span>
                </div>
            </div>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
<?php else:?>
<div class="tip">
		<span>
			<?php echo $this->translate("There are no stalls.") ?>
		</span>
</div>
<?php endif;?>
<div style="clear:both;"></div>
