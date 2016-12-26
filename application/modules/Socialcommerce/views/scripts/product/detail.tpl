<link rel="stylesheet" href="<?php echo $this->baseUrl()?>/application/modules/Socialcommerce/externals/styles/masterslider/masterslider.css" />

<!-- Master Slider Skin -->
<link rel="stylesheet" href="<?php echo $this->baseUrl()?>/application/modules/Socialcommerce/externals/styles/masterslider/masterslider-style.css" rel='stylesheet' type='text/css'>

<!-- MasterSlider Template Style -->
<link rel="stylesheet" href='<?php echo $this->baseUrl()?>/application/modules/Socialcommerce/externals/styles/masterslider/ms-lightbox.css' rel='stylesheet' type='text/css'>

<!-- Prettyphoto Lightbox jQuery Plugin -->
<link rel="stylesheet" href="<?php echo $this->baseUrl()?>/application/modules/Socialcommerce/externals/styles/masterslider/prettyPhoto.css"  rel='stylesheet' type='text/css'/>

<!-- jQuery -->
<script src="<?php echo $this->baseUrl()?>/application/modules/Socialcommerce/externals/scripts/jquery-1.6.1.min.js"></script>
<script src="<?php echo $this->baseUrl()?>/application/modules/Socialcommerce/externals/scripts/jquery.easing.min.js"></script>

<!-- Master Slider -->
<script src="<?php echo $this->baseUrl()?>/application/modules/Socialcommerce/externals/scripts/masterslider.min.js"></script>
<script src="<?php echo $this->baseUrl()?>/application/modules/Socialcommerce/externals/scripts/jquery.prettyPhoto.js"></script>

<div class="socialcommerce_style_1">
    <div class="socialcommerce-slider-master">
        <div class="socialcommerce-tab-content">
            <?php if(count($this->photos) > 0):?>
            <!-- template -->
            <div class="socialcommerce-photo-details ms-lightbox-template">
                <div class="master-slider ms-skin-default" id="masterslider">
                    <?php foreach($this->photos as $photo):?>
                    <?php if($this->product->photo_id == $photo->file_id):?>
                    <div class="ms-slide">
                        <img src="application/modules/Socialcommerce/externals/images/blank.gif" data-src="<?php echo $photo->getPhotoUrl(); ?>" alt="<?php echo $photo->title; ?>"/>
                        <img class="ms-thumb" src="<?php echo $photo->getPhotoUrl('thumb.normal'); ?>" alt="thumb" />
                        <a href="<?php echo $photo->getPhotoUrl(); ?>" class="ms-lightbox" rel="prettyPhoto[gallery1]" title="<?php echo $photo->title; ?>"></a>
                    </div>
                    <?php break; endif;?>
                    <?php endforeach;?>
                    <?php foreach($this->photos as $photo):?>
                    <?php if($this->product->photo_id != $photo->file_id):?>
                    <div class="ms-slide">
                        <img src="application/modules/Socialcommerce/externals/images/blank.gif" data-src="<?php echo $photo->getPhotoUrl(); ?>" alt="<?php echo $photo->title; ?>"/>
                        <img class="ms-thumb" src="<?php echo $photo->getPhotoUrl('thumb.normal'); ?>" alt="thumb" />
                        <a href="<?php echo $photo->getPhotoUrl(); ?>" class="ms-lightbox" rel="prettyPhoto[gallery1]" title="<?php echo $photo->title; ?>"></a>
                    </div>
                    <?php endif;?>
                    <?php endforeach;?>
                </div>
            </div>
            <!-- end of template -->

            <script type="text/javascript">
                jQuery.noConflict();

                var slider = new MasterSlider();
                slider.setup('masterslider' , {
                    width: 350,
                    height: 360,
                    space: 5,
                    loop: true,
                    autoplay: true,
                    speed: 10,
                    view: 'fade'
                });
                slider.control('arrows');
                slider.control('lightbox');
                slider.control('thumblist' , {autohide:false ,dir:'h'});

                jQuery(document).ready(function(){
                    jQuery("a[rel^='prettyPhoto']").prettyPhoto();
                });
            </script>
            <?php else:?>
            <div class="socialcommerce-photo-details ms-lightbox-template">
                <div class="master-slider ms-skin-default" id="masterslider">
                    <div class="ms-slide">
                        <img src="application/modules/Socialcommerce/externals/images/blank.gif" data-src="application/modules/Socialcommerce/externals/images/nophoto_product_thumb_main.png" alt="<?php echo $this->translate('No Photo')?>"/>
                        <img class="ms-thumb" src="application/modules/Socialcommerce/externals/images/nophoto_product_thumb_profile.png" alt="thumb" />
                        <a href="application/modules/Socialcommerce/externals/images/nophoto_product_thumb_profile.png" class="ms-lightbox" rel="prettyPhoto[gallery1]" title="<?php echo $this->translate('No Photo')?>"></a>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                jQuery.noConflict();

                var slider = new MasterSlider();
                slider.setup('masterslider' , {
                    width: 350,
                    height: 360,
                    space: 5,
                    loop: true,
                    autoplay: true,
                    speed: 10,
                    view: 'fade'
                });
                slider.control('arrows');
                slider.control('lightbox');
                slider.control('thumblist' , {autohide:false ,dir:'h'});

                jQuery(document).ready(function(){
                    jQuery("a[rel^='prettyPhoto']").prettyPhoto();
                });
            </script>
            <?php endif;?>
        </div>
    </div>

    <div class="socialcommerce-detail-content">
        <div class="product_title"><?php echo $this->product -> title; ?></div>

        <div class="product_review clearfix">
            <div class="product_rating">
                <?php echo $this->partial('_stall_rating_big.tpl', 'socialcommerce', array('stall' => $this->product)); ?>

                <span class="review">
                        <?php echo $this->product->ratingCount().' '.$this->translate('review(s)')?>
                    </span>

                <?php if ($this->can_review){
                echo $this->htmlLink(
                array(
                'route' => 'socialcommerce_review',
                'action' => 'create',
                'product_id' => $this->product->getIdentity(),
                'tab' => $this->identity,
                'page' => $this->page
                ),
                '<i class="ynicon yn-pencil-square-o"></i>'.$this->translate('Add your Review'),
                array(
                'class' => 'product-add-review smoothbox'
                )
                );
                } else if ($this->has_review) {
                echo $this->htmlLink(
                array(
                'route' => 'socialcommerce_review',
                'action' => 'edit',
                'id' => $this->my_review->getIdentity(),
                ),
                '<span>'.$this->translate('You have reviewed.').'</span>',
                array('class' => 'smoothbox'));
                }?>

            </div>
        </div>

        <div class="product_category_count">
            <div class="product_category">
                <i class="ynicon yn-folder-open"></i>
                <?php $category = $this->product->getCategory(); ?>
                <?php if ($category) echo $this->htmlLink($category->getHref(), $category->getTitle()) ?>
            </div>

            <div class="product_like_count">
                <?php echo $this->translate(array('<span class="count">%s</span> like', '<span class="count">%s</span> likes', $this->product->like_count), $this->product->like_count)?>
            </div>
        </div>

        <div class="product_currency">
            <?php echo $this -> locale()->toCurrency($this->product->price, Engine_Api::_() -> getApi('settings', 'core') -> getSetting('payment.currency', 'USD')); ?>
            <div class="socialcommerce_detail_block_button">
                <?php if (Engine_Api::_()->user()->getViewer()->getIdentity()): ?>
                <?php echo $this->htmlLink(array(
                'module'=>'activity',
                'controller'=>'index',
                'action'=>'share',
                'route'=>'default',
                'type'=>'socialcommerce_product',
                'id' => $this->product->getIdentity(),
                'format' => 'smoothbox'
                ), '<i class="fa fa-share-alt"></i>'.$this->translate("Share"), array('class' => 'socialcommerce_share_button smoothbox')); ?>

                <?php $isLiked = $this->product->likes()->isLike($this->viewer()) ? 1 : 0; ?>
                <a id="socialcommerce_like_button" class="socialcommerce_like_button" href="javascript:void(0);" onclick="onlike('<?php echo $this->product->getType() ?>', '<?php echo $this->product->getIdentity() ?>', <?php echo $isLiked ?>);">
                    <?php if( $isLiked ): ?>
                    <?php echo '<i class="fa fa-thumbs-up"></i>'.$this -> translate("Liked");?>
                    <?php else: ?>
                    <?php echo '<i class="fa fa-thumbs-up"></i>'.$this -> translate("Like");?>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="product_description rich_content_body"><?php echo $this->product->short_description?></div>

        <div class="product_contact">
            <?php echo $this->htmlLink(
            array(
            'route' => 'socialcommerce_specific',
            'action' => 'email-to-friends',
            'id' => $this->product->getIdentity()
            ),
            '<span class="fa fa-envelope"></span>'.$this->translate('Email to Friends'),
            array(
            'class' => 'smoothbox'
            )
            )?>

            <?php if ($this->product->isEditable() || $this->viewer()->isAdmin() || $this->product->getOwner()->isSelf(($this->viewer()))) : ?>
            <div class="socialcommerce_view_more">
                <span class="fa fa-caret-down"></span><?php echo $this->translate('More')?>

                <div class="socialcommerce_view_more_popup">

                    <?php if ($this->product->isEditable()) : ?>
                    <div id="edit">
                        <?php
                        echo $this->htmlLink(array(
                        'route' => 'socialcommerce_specific',
                        'action' => 'edit',
                        'id' => $this->product->getIdentity(),
                        ), '<i class="fa fa-pencil-square-o"></i>'.$this->translate('Edit listing'), array('class' => 'icon_socialcommerce_edit'));
                        ?>
                    </div>
                    <?php endif; ?>

                    <div id="manage_photo">
                        <?php
                        echo $this->htmlLink(array(
                        'route' => 'socialcommerce_general',
                        'controller' => 'photo',
                        'action' => 'manage',
                        'listing_id' => $this->product->getIdentity(),
                        ), '<i class="fa fa-picture-o"></i>'.$this->translate('Manage photos'), array('class' => 'icon_socialcommerce_edit'));
                        ?>
                    </div>

                    <div id="share">
                        <?php
                        echo $this->htmlLink(array(
                        'route' => 'default',
                        'module' => 'activity',
                        'controller' => 'index',
                        'action' => 'share',
                        'type' => 'socialcommerce_product',
                        'id' => $this->product->getIdentity(),
                        ), '<i class="fa fa-share-alt"></i>'.$this->translate('Share'), array('class' => 'icon_socialcommerce_edit'));
                        ?>
                    </div>

                    <div id="report">
                        <?php
                        echo $this->htmlLink(array(
                        'route' => 'default',
                        'module' => 'core',
                        'controller' => 'report',
                        'action' => 'create',
                        'subject' => $this->product->getGuid(),
                        ), '<i class="fa fa-exclamation-triangle"></i>'.$this->translate('Share'), array('class' => 'icon_socialcommerce_edit'));
                        ?>
                    </div>

                </div>
            </div>
            <?php endif; ?>
            <br/><br/>
            <!-- Add-This Button BEGIN -->
            <div class="addthis_sharing_toolbox"></div>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo Engine_Api::_() -> getApi('settings', 'core') -> getSetting('socialcommerce.addthis.pubid', 'ra-5773709668f9323d') ?>"></script>
            <!-- Add-This Button END -->
            <div class="product_description">
                <h4>Description</h4>
                <?php echo $this->viewMore(nl2br($this -> product -> description)); ?>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
function checkOpenPopup(url) {
      if(window.innerWidth <= 480)
      {
        Smoothbox.open(url, {autoResize : true, width: 300});
      }
      else
      {
        Smoothbox.open(url);
      }
}

function onlike(itemType, itemId, isLiked) {
    if (isLiked) {
        unlike(itemType, itemId);
    } else {
        like(itemType, itemId);
    }
}

function like(itemType, itemId)
{
    new Request.JSON({
        url: en4.core.baseUrl + 'core/comment/like',
        method: 'post',
        data : {
            format: 'json',
            type : itemType,
            id : itemId,
            comment_id : 0
        },
        onSuccess: function(responseJSON, responseText) {
            if (responseJSON.status == true)
            {
                var html = '<a id="socialcommerce_like_button" class="socialcommerce_like_button" href="javascript:void(0);" onclick="unlike(\'<?php echo $this->product->getType()?>\', \'<?php echo $this->product->getIdentity() ?>\')"><i class="fa fa-thumbs-up"></i><?php echo $this -> translate('Liked'); ?></a>';
                $("socialcommerce_like_button").outerHTML = html;
            }
        },
        onComplete: function(responseJSON, responseText) {
        }
    }).send();
}

function unlike(itemType, itemId)
{
    new Request.JSON({
        url: en4.core.baseUrl + 'core/comment/unlike',
        method: 'post',
        data : {
            format: 'json',
            type : itemType,
            id : itemId,
            comment_id : 0
        },
        onSuccess: function(responseJSON, responseText) {
            if (responseJSON.status == true)
            {
                var html = '<a id="socialcommerce_like_button" class="socialcommerce_like_button" href="javascript:void(0);" onclick="like(\'<?php echo $this->product->getType()?>\', \'<?php echo $this->product->getIdentity() ?>\')"><i class="fa fa-thumbs-up"></i><?php echo $this -> translate('Like'); ?></a>';
                $("socialcommerce_like_button").outerHTML = html;
            }
        }
    }).send();
}
</script>

<style>
    #global_page_socialcommerce-product-detail .socialcommerce_view_more_popup {
        width: auto;
    }
    #global_page_socialcommerce-product-detail .socialcommerce_view_more_popup i {
        margin-right: 5px;
    }
</style>