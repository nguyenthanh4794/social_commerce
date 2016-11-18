<link rel="stylesheet" href="<?php echo $this->baseUrl()?>/application/modules/Socialcommerce/externals/styles/aleofont.css" />
<?php
$coverPhotoUrl = "";
if ($this->stall->cover_id)
{
$coverPhotoUrl = $this->stall->getCoverPhotoUrl();
}
?>
<div class="socialcommerce-widget-profile-cover">
    <?php
		$stallPhotoUrl = ($this->stall->getPhotoUrl())
    ? ($this->stall->getPhotoUrl())
    : $this->layout()->staticBaseUrl . 'application/modules/Socialcommerce/externals/images/nophoto_stall_thumb_profile.png';
    ?>
    <div class="profile_cover_avata_title">
        <div class="profile-cover-avatar">
            <span style="background-image: url(<?php echo $stallPhotoUrl; ?>);"></span>
        </div>
        <div class="profile-cover-title">
            <span class="socialcommerce_stall_profile_name" title="Group Name"><?php echo $this->translate($this->stall->title) ?></span>
        </div>
        <div class="pu_clearfix"></div>
    </div>
    <?php if ($coverPhotoUrl!="") : ?>
    <div class="profile-cover-picture">
        <span class="profile-cover-picture-span" style="background-image: url(<?php echo $coverPhotoUrl; ?>);"></span>
    </div>
    <?php else : ?>
    <div class="profile-cover-picture">
        <span class="profile-cover-picture-span" style="background-image: url('application/modules/Socialcommerce/externals/images/socialcommerce_default_cover.jpg');"></span>
    </div>
    <?php endif; ?>
    <div class="socialcommerce-detail-info">
        <div class="info-middle ynclearfix">
            <div class="socialcommerce_details_owner" title="Group Owner">
                <img src="http://i.ebayimg.com/00/s/MzAwWDMwMA==/z/~wMAAOxyA7tSYa~H/$(KGrHqMOKpwFJgF3B3h)BSY,+Ghrw!~~60_7.JPG" alt="ebaydealseditor" class="long" style="display: inline;">
                <?php echo $this->translate($this->stall->getOwner()) ?>
            </div>
            <div class="socialcommerce-detail-more">
                <!-- Add-This Button -->
                <div class="addthis_sharing_toolbox"></div>
            </div>
            <div class="socialcommerce-detail-action">
                <?php if ($this->viewer() -> getIdentity()):
                $url = $this -> url(array(
                'module' => 'activity',
                'controller' => 'index',
                'action' => 'share',
                'type' => $this->stall -> getType(),
                'id' => $this->stall -> getIdentity(),
                'format' => 'smoothbox'),'default', true);?>
                <div>
                    <a href="javascript:void(0);" onclick="checkOpenPopup('<?php echo $url?>')">
                        Share
                    </a> |
                    <a href="">
                        Follow
                    </a>
                </div>
                <?php endif;?>
                <?php if ($this->viewer()->getIdentity()): ?>
                <?php if($this->aReportButton):?>
                <div class="">
                    <a href="<?php echo $this->url($this->aReportButton['params'],
		                	$this->aReportButton['route'], array());?>"
                       class="<?php echo $this->aReportButton['class'];?>"
                       title="<?php echo $this->aReportButton['label']; ?>"
                       style="background-image: url(<?php echo $this->aReportButton['icon']?>);" target="">
                        report
                    </a>
                </div>
                <?php endif;?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo Engine_Api::_()->getApi('settings', 'core') -> getSetting('socialcommerce.addthis.pubid', 'younet');?>" async="async"></script>
<script type="text/javascript">
    function checkOpenPopup(url)
    {
        if(window.innerWidth <= 480)
        {
            Smoothbox.open(url, {autoResize : true, width: 300});
        }
        else
        {
            Smoothbox.open(url);
        }
    }
</script>