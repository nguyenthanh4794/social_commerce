<?php if ($this->view_mode == 2): ?>
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

$this->headLink()->appendStylesheet($this->baseUrl() . '/application/modules/Socialcommerce/externals/styles/masterslider/ms-tabs-style.css');
$this->headLink()->appendStylesheet($this->baseUrl() . '/application/modules/Socialcommerce/externals/styles/masterslider/masterslider.css');
$this->headLink()->appendStylesheet($this->baseUrl() . '/application/modules/Socialcommerce/externals/styles/masterslider/skins/default/style.css');
?>

    <!-- template -->
    <div class="ms-tabs-template" id="">
        <!-- masterslider -->
        <div class="master-slider ms-skin-default" id="masterslider">
            <?php foreach($this->paginator as $stall) :?>

            <?php
                $stall_photo = ($stall->getCoverPhotoUrl('thumb.main')) ? $stall->getCoverPhotoUrl('thumb.main') : "application/modules/Socialcommerce/externals/images/nophoto_stall_thumb_profile.png";
            ?>

            <div class="ms-slide">
                <img src="application/modules/Socialcommerce/externals/images/blank.gif" data-src="<?php echo $stall_photo ?>" alt="lorem ipsum dolor sit"/>
                <div class="ms-thumb">
                    <span style="background-image: url('<?php echo $stall->getPhotoUrl(); ?>')"></span>
                    <h3 style="text-transform: uppercase"><?php echo $stall->getTitle(); ?></h3>
                    <p style="word-break: break-all;"><?php echo strip_tags($stall->getDescription()); ?></p>
                </div>
            </div>

            <?php endforeach; ?>
        </div>
        <!-- end of masterslider -->
    </div>
    <!-- end of template -->
<script type="text/javascript">

    var slider = new MasterSlider();

    slider.control('arrows');
    slider.control('circletimer' , {color:"#FFFFFF" , stroke:9});
    slider.control('thumblist' , {autohide:false ,dir:'h', type:'tabs',width:240,height:120, align:'bottom', space:0 , margin:-12, hideUnder:400});

    slider.setup('masterslider' , {
        width:1074,
        height:290,
        space:0,
        loop: true,
        preload:'all',
        view:'basic'
    });

</script>
<?php endif; ?>