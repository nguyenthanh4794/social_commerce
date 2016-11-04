<?php if ($this->view_mode == 1): ?>
<?php
    $this->headScript()->appendFile($this->baseUrl() . '/application/modules/Socialcommerce/externals/scripts/jquery-1.10.2.min.js')
->appendFile($this->baseUrl() . '/application/modules/Socialcommerce/externals/scripts/owl.carousel.js');
$this->headLink()->appendStylesheet($this->baseUrl() . '/application/modules/Socialcommerce/externals/styles/owl.carousel.css');
?>
<div class="socialcommerce-featured-slider-style-2">
    <div class="socialcommerce-featured-slider owl-carousel" id="featured-owl-demo">
    <?php foreach($this->paginator as $stall) :?>
    <?php
		$stall_photo = ($stall->getCoverPhotoUrl('thumb.main')) ? $stall->getCoverPhotoUrl('thumb.main') : "application/modules/Socialcommerce/externals/images/nophoto_stall_thumb_profile.png";
    ?>
    <div class="featured-item item" style="background-image: url(<?php echo $stall_photo ?>) ">
        <div class="featured-item-infomation">

            <div class="stall_title">
                <a href="<?php echo $stall->getHref(); ?>"><?php echo $stall->title; ?></a>
            </div>

            <div class="stall-rating-desc-price">
                <div class="stall_rating">
                    <?php
                       echo $this->partial('_stall_rating_big.tpl', 'socialcommerce', array('stall' => $stall));
                    ?>

                    <span class="review">
                        <?php echo $stall->ratingCount().' '.$this->translate('review(s)')?>
                    </span>
                </div>

                <div class="short_description">
                    <?php echo strip_tags($stall->description)?>
                </div>
            </div>

        </div>
    </div>
    <?php endforeach; ?>
</div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#featured-owl-demo").owlCarousel({
            navigation : true,
            slideSpeed : 500,
            nav:true,
            loop:true,
            center:true,
            autoHeight:true,
            margin:10,
            paginationSpeed : 600,
            singleItem : true,
            autoPlay: true,
            responsive:{
                600:{
                    items:4
                }
            }
        });
    });
</script>
<?php else: ?>
<?php
$this->headScript()
->appendFile($this->baseUrl() . '/application/modules/Socialcommerce/externals/scripts/jquery-1.10.2.min.js')
->appendFile($this->baseUrl() . '/application/modules/Socialcommerce/externals/scripts/jquery.easing.min.js')
->appendFile($this->baseUrl() . '/application/modules/Socialcommerce/externals/scripts/masterslider.min.js');

$this->headLink()->appendStylesheet($this->baseUrl() . '/application/modules/Socialcommerce/externals/styles/masterslider/ms-partialview.css');
$this->headLink()->appendStylesheet($this->baseUrl() . '/application/modules/Socialcommerce/externals/styles/masterslider/masterslider.css');
$this->headLink()->appendStylesheet($this->baseUrl() . '/application/modules/Socialcommerce/externals/styles/masterslider/masterslider-style.css');
?>

<div class="socialcommerce-featured-slider-style-1">
    <!-- template -->
    <div class="ms-partialview-template" id="partial-view-1">
        <!-- masterslider -->
        <div class="master-slider ms-skin-default" id="masterslider">
            <?php foreach($this->paginator as $stall) :?>

            <?php
                $stall_photo = ($stall->getPhotoUrl('thumb.main')) ? $stall->getPhotoUrl('thumb.main') : "application/modules/Socialcommerce/externals/images/nophoto_stall_thumb_profile.png";
            ?>

            <div class="ms-slide">
                <img src="application/modules/Socialcommerce/externals/images/blank.gif" data-src="<?php echo $stall_photo ?>" alt="lorem ipsum dolor sit"/>
                <div class="ms-info">
                    <div class="stall_title">
                        <a href="<?php echo $stall->getHref(); ?>"><?php echo $stall->title; ?></a>
                    </div>

                    <div class="stall-rating-price">
                        <div class="stall_rating">
                            <?php
                               echo $this->partial('_stall_rating_big.tpl', 'socialcommerce', array('stall' => $stall));
                            ?>
                        </div>
                    </div>

                    <div class="short_description">
                        <?php echo strip_tags($stall->description)?>
                    </div>

                </div>
            </div>

            <?php endforeach; ?>
        </div>
        <!-- end of masterslider -->
    </div>
    <!-- end of template -->
</div>
<script type="text/javascript">

    jQuery.noConflict();
    var slider = new MasterSlider();
    slider.control('arrows');
    slider.control('slideinfo',{insertTo:"#partial-view-1" , autohide:false, align:'bottom', size:160});
    slider.control('circletimer' , {color:"#FFFFFF" , stroke:9});

    slider.setup('masterslider' , {
        width:450,
        height:255,
        space:30,
        loop:true,
        autoplay: true,
        view:'partialWave',
        layout:'partialview'
    });

</script>
<?php endif; ?>