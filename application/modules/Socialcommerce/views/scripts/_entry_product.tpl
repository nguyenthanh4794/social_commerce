<?php $this->headScript()
->appendFile($this->baseUrl().'/application/modules/Socialcommerce/externals/scripts/core.js'); ?>

<li class="socialcommerce-item" data-product-id="<?php echo $this->item->getIdentity() ?>" id="js_product_id_<?php echo $this->item->getIdentity() ?>">
    <div class="socialcommerce-item-content socialcommerce-product-listing">
        <div class="socialcommerce-bg-block">
            <?php
                if ($this->item->photo_id)
                    $photoUrl = $this->item->getPhotoUrl('thumb.main');
                else
                    $photoUrl = $this->getNoPhoto($this->item, 'thumb.main');
            ?>
            <a class="socialcommerce-bg" href="<?php $this->item->getHref() ?>" style="background-image: url(<?php echo $photoUrl ?>)"></a>

            <div class="socialcommerce-featured">
                <div title="<?php echo $this->translate('Featured') ?>" class="socialcommerce-featured-triangle socialcommerce_entry_feature_icon-<?php echo $this->item->getIdentity() ?>" style="<?php if ($this->item->featured) echo 'visibility: hidden'; ?>">
                    <i class="ynicon yn-diamond"></i>
                </div>
            </div>
        </div>

        <div class="socialcommerce-info">
            <div class="socialcommerce-info-detail">
                <div class="socialcommerce-product-limit-dynamic">
                    <a title="<?php echo $this->item->title ?>" href="<?php echo $this->item->getHref() ?>" class="socialcommerce-title">
                        <?php echo $this->item->title ?>
                    </a>
                    <div class="socialcommerce-product-from">
                        <span><?php echo $this->translate('From')?></span>
                        <?php $stall = $this->item->getStall(); if ($stall): ?>
                        <a title="<?php echo $stall->title ?>" href="<?php echo $stall->getHref() ?>"><?php echo $stall->title ?></a>
                        <?php endif; ?>
                    </div>

                    <div class="socialcommerce-product-description item_view_content" style="display: none;">
                        <?php echo $this->item->short_description ?>
                    </div>
                </div>

                <div class="socialcommerce-product-block4list">
                    <div class="socialcommerce-product-pullleft">
                        <div class="socialcommerce-price">
                            <?php echo $this->item->getCurrency().$this->item->price ?>
                        </div>
                    </div>

                    <div class="socialcommerce-rating-count-block">
                            <span class="socialcommerce-rating yn-rating yn-rating-small">
                            <?php $rating = $this->item->getRating();
                                for($i = 0; $i < 5; $i++): ?>
                                <?php if ($i < $rating): ?>
                                    <i class="ynicon yn-star" aria-hidden="true"></i>
                                <?php elseif ((($rating - round($rating)) > 0) && ($rating - $i) > 0): ?>
                                    <i class="ynicon yn-star-half-o" aria-hidden="true"></i>
                                <?php else: ?>
                                    <i class="ynicon yn-star yn-rating-disable" aria-hidden="true"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </span>

                        <span class="socialcommerce-count-statistic" style="display: none">
                                11&nbsp;orders
                        </span>
                    </div>
                </div>


                <?php $viewer = Engine_Api::_()->user()->getViewer(); ?>
                <?php if ($this->item->owner_id != $viewer->getIdentity()): ?>
                <div title="<?php echo $this->translate('Add to cart') ?>" class="socialcommerce-btn socialcommerce-addtocart-btn" onclick="javascript:en4.store.cart.addProductBox(<?php echo $this->item->getIdentity() ?>)" data-addtocartid="<?php echo $this->item->getIdentity() ?>" style="display:none">
                    <i class="ynicon yn-cart-plus"></i>
                    <?php echo $this->translate('Add to cart') ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="socialcommerce-featured socialcommerce-featured-4list" style="display: none;">
            <div title="<?php echo $this->translate('Featured')?>" class="socialcommerce-featured-triangle socialcommerce_entry_feature_icon-<?php echo $this->item->getIdentity() ?>" style="<?php if ($this->item->featured) echo 'visibility: hidden'; ?>">
                <i class="ynicon yn-diamond"></i>
            </div>
        </div>

        <?php echo $this->partial('_link_manage_product.tpl', 'socialcommerce', array('item' => $this->item)); ?>
    </div>
</li>