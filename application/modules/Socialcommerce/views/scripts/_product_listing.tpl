<?php if(count($this->products) > 0):?>
<div class="socialcommerce-tabs-content ynclearfix">
    <div id="tab_products_recent" class="tabcontent" style="display: block;">
        <ul class="generic_list_widget product_browse product_browse_view_content yn-layout-gridview clearfix">
            <?php foreach( $this->products as $product ): ?>
            <li>
                <div class="grid-view">
                    <div class="socialcommerce-grid-item">
                        <div class="socialcommerce-grid-item-content">
                            <?php $photo_url = ($product->getPhotoUrl('thumb.profile')) ? $product->getPhotoUrl('thumb.profile') : "application/modules/Socialcommerce/externals/images/nophoto_product_thumb_profile.png";?>
                            <div class="item-background" style="background-image: url(<?php echo $photo_url; ?>);">

                                <?php if ($product->featured) : ?>
                                <div class="yn-attr-block yn-corner-left-top">
                                    <label class="yn-label-featured-txt">
                                        <i class="ynicon yn-diamond"></i>
                                        Featured
                                    </label>
                                </div>
                                <?php endif; ?>

                                <?php if ($product->isNew()) : ?>
                                <div class="yn-attr-block yn-corner-right-top">
                                    <label class="yn-label-sponsored-txt">
                                        <i class="ynicon yn-blast"></i>
                                        New
                                    </label>
                                </div>
                                <?php endif; ?>

                            </div>
                            <div class="item-front-info">
                                <div class="product_title">
                                    <?php echo $this->htmlLink($product->getHref(), $product->title);?>
                                </div>

                                <div class="product_price">
                                    <?php echo $this -> locale()->toCurrency($product->price, Engine_Api::_() -> getApi('settings', 'core')->getSetting('payment.currency', 'USD'))?>
                                </div>

                            </div>
                        </div>
                        <div class="socialcommerce-grid-item-hover">
                            <div class="socialcommerce-grid-item-hover-background">
                                <div class="product_view_more">
                                    <?php echo $this->htmlLink($product->getHref(), $this->translate('View more <span class="fa fa-arrow-right"></span> ') );?>
                                </div>

                                <div class="product_add_cart">
                                    <?php echo $this->htmlLink($product->getHref(), $this->translate('Add to Cart <span class="fa fa-cart-plus"></span> ') );?>
                                </div>

                                <div class="short_description">
                                    <?php echo strip_tags($product->description)?>
                                </div>

                                <div class="product_creation">
                                    <span class="author-avatar"><?php echo $this->htmlLink($product->getOwner(), $this->itemPhoto($product->getOwner(), 'thumb.icon'))?></span>
                                    <span><?php echo $this->translate('by ')?></span>
                                    <span class="author-title"><?php echo $product->getOwner()?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php else: ?>
<div class="tip">
    <span>
        <?php echo $this->translate("There are no products.") ?>
    </span>
</div>
<?php endif; ?>
