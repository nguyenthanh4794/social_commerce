<div class="socialcommerce-profile-module-header">
    <div class="socialcommerce-profile-header-right">
    <?php
    	if(count($this->paginator) > 0)
    	{
    		echo $this->htmlLink(array(
    	          'route' => 'socialcommerce_extended',
    	          'controller' => 'video',
    	          'action' => 'list',
    	          'subject' => $this->subject()->getGuid(),
    	          'tab' => $this->identity,
    	        ), '<i class="ynicon yn-list-view"></i>'.$this->translate('View All Videos'), array(
    	          'class' => 'buttonlink'
    	      ));
    	}
    	if( $this->canCreate ): 
    			echo $this->htmlLink(array(
    				'route' => 'video_general',
    				'action' => 'create',
    				'parent_type' =>'socialcommerce_stall',
    				'subject_id' =>  $this->stall->stall_id,
    			), '<i class="ynicon yn-plus-circle"></i>'.$this->translate('Create New Video'), array(
    			'class' => 'buttonlink'
    			)) ;
    	?>
    <?php endif; ?>
    </div>
    <div class="socialcommerce-profile-header-content">
        <?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
            <span class="socialcommerce-numeric"><?php echo $this->paginator->getTotalItemCount(); ?></span>
            <?php echo $this-> translate(array("video", "Videos", $this->paginator->getTotalItemCount()), $this->paginator->getTotalItemCount());?>
        <?php endif; ?>
    </div>
</div>

<?php if($this->paginator->getTotalItemCount() > 0 ):?>
<script type="text/javascript">
en4.core.runonce.add(function()
{
    var anchor = $('socialcommerce_profile_videos').getParent();
    $('socialcommerce_profile_videos_previous').style.display = '<?php echo ( $this->paginator->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>';
    $('socialcommerce_profile_videos_next').style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>';

    $('socialcommerce_profile_videos_previous').removeEvents('click').addEvent('click', function(){
      en4.core.request.send(new Request.HTML({
        url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
        data : {
          format : 'html',
          subject : en4.core.subject.guid,
          page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() - 1) ?>
        }
      }), {
        'element' : anchor
      })
    });

    $('socialcommerce_profile_videos_next').removeEvents('click').addEvent('click', function(){
      en4.core.request.send(new Request.HTML({
        url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
        data : {
          format : 'html',
          subject : en4.core.subject.guid,
          page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
        }
      }), {
        'element' : anchor
      })
    });
  });
</script>
<ul class="generic_list_widget yn-layout-gridview" id="socialcommerce_profile_videos" style="padding-bottom:0px;">
    <?php foreach ($this->paginator as $item): ?>
        <li <?php echo isset($this->marginLeft)?'style="margin-left:' . $this->marginLeft . 'px"':''?>>
            <?php
            echo $this->partial('_video_listing.tpl', 'socialcommerce', array(
                'video' => $item,
                'recentCol' => $this->recentCol
            ));
            ?>
        </li>
        
    <?php endforeach; ?>
</ul>
<div class="socialcommerce-paginator">
  <div id="socialcommerce_profile_videos_previous" class="paginator_previous">
    <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
      'onclick' => '',
      'class' => 'buttonlink icon_previous'
    )); ?>
  </div>
  <div id="socialcommerce_profile_videos_next" class="paginator_next">
    <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
      'onclick' => '',
      'class' => 'buttonlink_right icon_next'
    )); ?>
  </div>
</div>
<?php else:?>
  <div class="tip">
    <span>
      <?php echo $this->translate('No videos have been added in this stall yet.');?>
    </span>
  </div>
<?php endif;?>