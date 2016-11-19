<?php if(count($this->paginator) > 0): ?>
<div class="scstall-my-store-page" id="js_block_border_ynsocialstore_store_managestore">
    <ul class="scstall-items">
        <?php foreach($this->paginator as $stall): ?>
        <li class="scstall-item">
            <div class="scstall-item-content">
                <?php $photo_url = ($stall->getPhotoUrl('thumb.profile')) ? $stall->getPhotoUrl('thumb.profile') : "application/modules/Socialcommerce/externals/images/nophoto_stall_thumb_profile.png";?>
                <div class="scstall-bg"
                     style="background-image: url(<?php echo $photo_url ?>)">

                    <div id="scstall_status_<?php echo $stall->getIdentity() ?>" class="scstall-status-block">
                        <div class="scstall-status ynstatus_<?php echo $stall->status ?>">
                            <?php echo $stall->status ?>
                        </div>
                    </div>
                </div>

                <?php
                    echo $this->partial('_action_block.tpl', 'socialcommerce', array(
                        'item' => $stall,
                    ));
                ?>

                <div class="scstall-featured">
                    <div title="<?php echo $this->translate('Featured'); ?>"
                         class="scstall-featured-triangle scstall_entry_feature_icon-<?php echo $stall->getIdentity() ?>"
                         <?php if(!$stall->is_featured) echo 'style="visibility:hidden"' ?>>
                        <i class="ynicon yn-diamond"></i>
                    </div>
                </div>

                <div class="scstall-info">
                    <a href="<?php echo $stall->getHref(); ?>"
                       class="scstall-title">
                        <?php echo $stall->title ?>
                    </a>
                    <div class="scstall-info-detail">
                        <div class="scstall-date">
                            <?php echo $this->translate('Created on'). ' ' . $this->timestamp($stall->creation_date).' '.$this->translate('by').' '.$this->htmlLink($stall->getOwner()->getHref(), $stall->getOwner()->getTitle())?>
                        </div>

                        <div class="scstall-categories">
                            <div class="scstall-categories-content">
                                <?php echo $this->translate('Category:'); ?>
                                <?php $category = $stall->getCategory(); ?>
                                <?php echo $this->htmlLink($category->getHref(), $category->getTitle()); ?>
                            </div>
                        </div>
                    </div>

                    <div class="scstall-package-product">
                        <span>
                            <label><?php echo $this->translate('Orders'); ?></label>
                            <?php echo $stall->total_orders ?>
                        </span>
                        <span>
                            <label><?php echo $this->translate('Products'); ?></label>
                            <?php echo $stall->total_products ?>
                        </span>
                    </div>
                </div>

                <div class="scstall-statistic-block">
                    <span class="scstall-statistic scstall-follows">
                        <?php echo $this->translate(array('%1$s follower', '%1$s followers', $stall->total_follow), '<b>'.$this->locale()->toNumber($stall->total_follow).'</b>') ?>
                    </span>

                    <span class="scstall-statistic scstall-orders">
                        <?php echo $this->translate(array('%1$s order', '%1$s orders', $stall->total_orders), '<b>'.$this->locale()->toNumber($stall->total_orders).'</b>') ?>
                    </span>

                    <span class="scstall-statistic scstall-favorites scstall-flag">
                        <?php echo $this->translate(array('%1$s favorite', '%1$s favorites', $stall->total_favorite), '<b>'.$this->locale()->toNumber($stall->total_favorite).'</b>') ?>
                    </span>

                    <span class="scstall-statistic scstall-views scstall-flag">
                        <?php echo $this->translate(array('%1$s view', '%1$s views', $stall->total_view), '<b>'.$this->locale()->toNumber($stall->total_view).'</b>') ?>
                    </span>

                    <span class="scstall-statistic scstall-likes">
                        <?php echo $this->translate(array('%1$s like', '%1$s likes', $stall->likes()->getLikeCount()), '<b>'.$this->locale()->toNumber($stall->likes()->getLikeCount()).'</b>') ?>
                    </span>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php else: ?>
<div class="tip">
    <span><?php echo $this->translate('There are no stall available'); ?></span>
</div>
<?php endif; ?>