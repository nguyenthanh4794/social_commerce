<?php if(count($this->stalls) > 0):?>
<ul class="generic_list_widget stall_browse">
    <?php foreach( $this->stalls as $stall ): ?>
    <li>
        <div class="list-view socialcommerce-list-item">
            <div class="stall_photo">
                <?php $photo_url = ($stall->getPhotoUrl('thumb.profile')) ? $stall->getPhotoUrl('thumb.profile') : "application/modules/Socialcommerce/externals/images/nophoto_stall_thumb_profile.png";?>
                <div class="stall_photo_main" style="background-image: url(<?php echo $photo_url; ?>);">
                    <div class="stall_photo_hover">
                        <div class="stall_view_more">
                            <?php echo $this->htmlLink($stall->getHref(), $this->translate('View more <span class="fa fa-arrow-right"></span> ') );?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stall_info">
                <div class="stall_title">
                    <?php echo $this->htmlLink($stall->getHref(), $stall->title);?>
                </div>

                <div class="short_description">
                    <?php echo strip_tags($stall->description)?>
                </div>

                <div class="stall_info_footer">
                    <div class="author-avatar">
                        <?php echo $this->htmlLink($stall->getOwner(), $this->itemPhoto($stall->getOwner(), 'thumb.icon'))?>
                    </div>

                    <div class="stall_info_footer_main">
                        <div>
                            <div class="stall_creation">
                                <span class=""><?php echo $this->translate('by ')?></span>
                                <span><?php echo $stall->getOwner()?></span>
                            </div>

                            <div class="category">
                                <?php $category = $stall->getCategory()?>
                                <?php if ($category) : ?>
                                <span class="fa fa-folder-open-o"></span>
                                <span><?php echo ' '.$this->htmlLink($category->getHref(), $category->getTitle())?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <div class="stall_rating">

                                <span><?php
                                    echo $this->partial('_stall_rating_big.tpl', 'socialcommerce', array('stall' => $stall));
                                    ?>
                                </span>

                                <span class="review">
                                    <?php echo $stall->ratingCount().' '.$this->translate('review(s)')?>
                                </span>
                            </div>
                        </div>
                    </div>
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