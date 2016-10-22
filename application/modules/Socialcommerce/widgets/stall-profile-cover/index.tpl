<?php
$coverPhotoUrl = "";
if ($this->stall->cover_photo)
{
$coverFile = Engine_Api::_()->getDbtable('files', 'storage')->find($this->stall->cover_photo)->current();
if($coverFile)
$coverPhotoUrl = $coverFile->map();
}
?>
<div class="socialcommerce-widget-profile-cover">
    <?php
		$stallPhotoUrl = ($this->stall->getPhotoUrl())
    ? ($this->stall->getPhotoUrl())
    : $this->layout()->staticBaseUrl . 'application/modules/Socialcommerce/externals/images/nophoto_stall_thumb_profile.png';
    ?>

    <?php if ($coverPhotoUrl!="") : ?>
    <div class="profile-cover-picture">
        <span class="profile-cover-picture-span" style="background-image: url(<?php echo $coverPhotoUrl; ?>);"></span>
    </div>
    <?php else : ?>
    <div class="profile-cover-picture">
        <span class="profile-cover-picture-span" style="background-image: url('application/modules/Socialcommerce/externals/images/socialcommerce_default_cover.jpg');"></span>
    </div>
    <?php endif; ?>
    <div class="profile-cover-avatar">
        <span style="background-image: url(<?php echo $stallPhotoUrl; ?>);"></span>
    </div>
    <div class="socialcommerce-detail-info">
        <div class="info-top ynclearfix">
            <div class="socialcommerce-detail-action">
                <?php if ($this->viewer() -> getIdentity()):
                $url = $this -> url(array(
                'module' => 'activity',
                'controller' => 'index',
                'action' => 'share',
                'type' => $this->stall -> getType(),
                'id' => $this->stall -> getIdentity(),
                'format' => 'smoothbox'),'default', true);?>

                <div class="">
                    <a href="javascript:void(0);" onclick="checkOpenPopup('<?php echo $url?>')"><i class="ynicon-share" title="<?php echo $this -> translate("Share this stall")?>"></i></a>
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

                <?php if ($this->viewer()->getIdentity()): ?>
                <div id="socialcommerce_widget_cover_settings"><i class="ynicon-setting" title="<?php echo $this -> translate("Group options")?>"></i></div>
                <?php endif;?>
            </div>
            <div class="socialcommerce-detail-main">
                <div>
                    <strong title="Group Name"><?php echo $this->translate($this->stall->title) ?></strong>
                    by
                    <strong title="Group Owner">
                        <a href=""><?php echo $this->translate($this->stall->getOwner()) ?></a>
                    </strong>
                </div>
                <div>
					<span>
						<i class="ynicon yn-alarm" title="Time create"></i>
                        <?php echo $this->timestamp(strtotime($this->stall->creation_date)); ?>
					</span>
                </div>
                <?php if($this->stall->location != ""):?>
                <div class="location-info">
					<span title="<?php echo $this->stall->location; ?>">
						<i class="ynicon yn-location" title="Location"></i>
                        <?php echo $this->stall->location; ?>
					</span>
                </div>
                <?php endif;?>
            </div>
        </div>

        <div class="info-bottom ynclearfix">
            <div class="socialcommerce-detail-more">
                <!-- Add-This Button -->
                <div class="addthis_sharing_toolbox"></div>
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