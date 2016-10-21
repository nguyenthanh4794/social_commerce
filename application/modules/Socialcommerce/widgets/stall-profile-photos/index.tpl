<?php if( $this->paginator->getTotalItemCount() > 0 || $this->canUpload ):
$this->headScript()
  ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Ynbusinesspages/externals/scripts/js/businesspages.js');
$photo_listing_id = "ynbusinesspages_profile_photos";
    if (!isset($this->class_mode)) $this->class_mode = 'ynbusinesspages-grid-view';
    if (!isset($this->view_mode)) $this->view_mode = 'grid';
?>
<div class="ynbusinesspages-profile-module-header">
    <div class="ynbusinesspages-profile-header-right">
        <!-- Menu Bar -->
    <?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
      <?php echo $this->htmlLink(array(
          'route' => 'ynbusinesspages_extended',
          'controller' => 'photo',
          'action' => 'list',
          'subject' => $this->subject()->getGuid(),
          'tab' => $this->identity,
        ), '<i class="ynicon yn-list-view"></i>'.$this->translate('View All Photos'), array(
          'class' => 'buttonlink'
      )) ?>
    <?php endif; ?>
   
      <?php if( $this->canUpload ): ?>
        <?php echo $this->htmlLink(array(
            'route' => 'ynbusinesspages_extended',
            'controller' => 'photo',
            'action' => 'upload',
            'subject' => $this->subject()->getGuid(),
            'tab' => $this->identity,
          ), '<i class="ynicon yn-plus-circle"></i>'.$this->translate('Upload Photos'), array(
            'class' => 'buttonlink'
        )) ?>
      <?php endif; ?>
    </div> 
    <div class="ynbusinesspages-profile-header-content">
        <?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
            <span class="ynbusinesspages-numeric"><?php echo $this->paginator->getTotalItemCount(); ?></span> 
            <?php echo $this-> translate(array("ynbusiness_photo", "Photos", $this->paginator->getTotalItemCount()), $this->paginator->getTotalItemCount());?>
        <?php endif; ?>
    </div>     
</div>
<?php endif; ?>

<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
<script type="text/javascript">
en4.core.runonce.add(function()
{
    var anchor = $('ynbusinesspages_profile_photos').getParent();
    $('ynbusinesspages_profile_photos_previous').style.display = '<?php echo ( $this->paginator->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>';
    $('ynbusinesspages_profile_photos_next').style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>';

    $('ynbusinesspages_profile_photos_previous').removeEvents('click').addEvent('click', function(){
      en4.core.request.send(new Request.HTML({
        url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
        data : {
          format : 'html',
          subject : en4.core.subject.guid,
          page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() - 1) ?>
                    },
                    onSuccess: function() {
                        setTimeout(ynbusinessPhotoInitViewMode, 200);
        }
      }), {
        'element' : anchor
      })
    });

    $('ynbusinesspages_profile_photos_next').removeEvents('click').addEvent('click', function(){
      en4.core.request.send(new Request.HTML({
        url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
        data : {
          format : 'html',
          subject : en4.core.subject.guid,
          page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
                    },
                    onSuccess: function() {
                        setTimeout(ynbusinessPhotoInitViewMode, 200);
        }
      }), {
        'element' : anchor
      })
    });
  });
</script>
<div id="<?php echo $photo_listing_id; ?>">
  <div class="ynbusinesspages-listing-tab">
            <div title="<?php echo $this->translate('Grid view');?>" class="grid-view" data-view="grid"></div>
            <div title="<?php echo $this->translate('Pinterest view');?>" class="pinterest-view" data-view="pinterest"></div>
  </div>
  
  <div class="photo-list-content ynbusinesspages-grid-view" >
    <!-- grid view -->
    <ul class="thumbs" id="ynbusinesspages_profile_photos">
        <?php 
    		$thumb_photo = 'thumb.profile';
        foreach( $this->paginator as $photo ): ?>
          <li>
            <div class="ynbusinesspages_photo_items">
              <a  class="thumbs_photo" href="<?php echo $photo->getHref(); ?>"></a>
              <span class="ynbusinesspages_photo" style="background-image: url(<?php echo $photo->getPhotoUrl($thumb_photo); ?>);"></span>
            </div>
          </li>
        <?php endforeach;?>
    </ul>
    <!-- printer view -->
    <ul id="<?php echo $photo_listing_id; ?>_tiles" class="photo-pinterest-view gallery<?php echo $this->rand; ?> clearfix">
      <?php foreach( $this->paginator as $photo ): ?>
        <li class="ynbusinesspages_photo_items ynbusinesspages_pinterest" id="thumbs-photo-<?php echo $photo->photo_id ?>" class="swiper-slide">
          <div class="ynbusinesspages_photo_items_thumbs">
            <a class="ynbusinesspages_photo_items_temp" href="<?php echo $photo->getHref(); ?>"></a>
            <img class="ynadvalbum_pinteres_thumbs-photo" src="<?php echo $photo->getPhotoUrl($thumb_photo); ?>" />
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<div class="ynbusinesspages-paginator">
  <div id="ynbusinesspages_profile_photos_previous" class="paginator_previous">
    <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
      'onclick' => '',
      'class' => 'buttonlink icon_previous'
    )); ?>
  </div>
  <div id="ynbusinesspages_profile_photos_next" class="paginator_next">
    <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
      'onclick' => '',
      'class' => 'buttonlink_right icon_next'
    )); ?>
  </div>
</div>
<?php else: ?>

  <div class="tip">
    <span>
      <?php echo $this->translate('No photos have been uploaded to this business yet.');?>
    </span>
  </div>

<?php endif; ?>

<script type="text/javascript" src="<?php echo $this->layout()->staticBaseUrl?>application/modules/Ynbusinesspages/externals/scripts/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="<?php echo $this->layout()->staticBaseUrl?>application/modules/Ynbusinesspages/externals/scripts/wookmark/jquery.imagesloaded.js"></script>
  <script type="text/javascript" src="<?php echo $this->layout()->staticBaseUrl?>application/modules/Ynbusinesspages/externals/scripts/wookmark/jquery.wookmark.js"></script>

<script type="text/javascript">
     jQuery.noConflict();
    window.addEvent('domready', function () {
        ynbusinessPhotoInitViewMode();
        ynbusinesspagesOptions();
    });

    en4.core.runonce.add(function()
    {
        $$('#main_tabs li.tab_layout_ynbusinesspages_business_profile_photos').addEvent('click', function(){
            ynbusinessPhotoInitViewMode();
            });

         });

    function ynbusinessPhotoInitViewMode() {

        var view_mode = getCookie('<?php echo $photo_listing_id; ?>view_mode') || '<?php echo $this->view_mode ?>' || 'grid';
        ynbusinessPhotoSetViewMode(view_mode);

        $$('#<?php echo $photo_listing_id; ?> .ynbusinesspages-listing-tab > div').removeEvents('click').addEvent('click', function(){
            var view_mode = this.get('data-view');
            ynbusinessPhotoSetViewMode(view_mode);
        });

            }

    function ynbusinessPhotoSetViewMode(view_mode) {

        $$('#<?php echo $photo_listing_id; ?> .photo-list-content').set('class', 'photo-list-content ynbusinesspages-'+ view_mode +'-view');
        $$('#<?php echo $photo_listing_id; ?> .ynbusinesspages-listing-tab > div').removeClass('active');
        $$('#<?php echo $photo_listing_id; ?> .ynbusinesspages-listing-tab > div.' + view_mode + '-view').addClass('active');
        if(view_mode == "pinterest" )
        {
            var handler = jQuery('#<?php echo $photo_listing_id; ?>_tiles li.ynbusinesspages_pinterest');
            var options = {
                itemWidth: 215,
                  autoResize: true,
                  container: jQuery('#<?php echo $photo_listing_id; ?>_tiles'),
                  offset: 25,
                  outerOffset: 0,
                  flexibleWidth: '50%'
            };

            if ( jQuery(window).width() < 1024) {
                options.flexibleWidth = '100%';
            }
            handler.wookmark(options);
        }

        setCookie('<?php echo $photo_listing_id; ?>view_mode', view_mode);
    }

  function setCookie(cname,cvalue,exdays)
    {
    var d = new Date();
    d.setTime(d.getTime()+(exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
  }

  function getCookie(cname)
  {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++)
    {
      var c = ca[i].trim();
      if (c.indexOf(name)==0) return c.substring(name.length,c.length);
    }
    return "";
  }
</script>