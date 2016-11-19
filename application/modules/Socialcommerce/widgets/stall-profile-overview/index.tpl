<h3><?php echo $this->translate('About the ').$this->stall->title; ?></h3>
<?php if ($this->viewer() -> getIdentity()):
$url = $this -> url(array(
'module' => 'socialcommerce',
'controller' => 'stall',
'action' => 'edit-info',
'type' => $this->stall -> getType(),
'id' => $this->stall -> getIdentity(),
'format' => 'smoothbox'),'default', true);?>
<div class="socialcommerce_edit_stall_button">
    <a href="javascript:void(0);" onclick="checkOpenPopup('<?php echo $url?>')"><i class="ynicon yn-pencil-square-o" title="<?php echo $this -> translate("Edit this stall")?>"></i></a>
</div>
<?php endif;?>
<div class="socialcommerce-profile-fields" style="clear: both">
    <div class="socialcommerce-overview-title socialcommerce-overview-line">
        <span class="socialcommerce-overview-toggle-button"><i class="ynicon yn-arr-down"></i></span>
        <span class="socialcommerce-overview-title-content"><i class="ynicon yn-building"></i> <?php echo $this->translate('Address');?></span>
    </div>
    <div class="socialcommerce-overview-content">
        <ul class="socialcommerce-overview-listmaps">
            <li>
                <div class="socialcommerce-overview-maps-title"><?php echo $this-> stall -> location ?></div>
                <div class="socialcommerce-overview-maps-location"><i class="ynicon yn-map-marker"></i> <?php echo $this-> stall -> location ?></div>
                <?php if ($this-> stall -> latitude != '0' && $this-> stall -> longitude != '0'):?>
                <?php
						echo $this -> partial('_location_map.tpl', 'socialcommerce', array(
                'item' => $this-> stall,
                'map_canvas_id' => 'map-canvas',
                ));
                ?>
                <?php endif;?>
            </li>
        </ul>
    </div>
</div>

<div class="socialcommerce-profile-fields">
    <div class="socialcommerce-overview-title socialcommerce-overview-line">
        <span class="socialcommerce-overview-toggle-button"><i class="ynicon yn-arr-down"></i></span>
        <span class="socialcommerce-overview-title-content"><i class="ynicon yn-text-align-justify"></i><?php echo $this->translate('Short Description');?></span>
    </div>
    <div class="socialcommerce-overview-content">
        <div class="socialcommerce-description rich_content_body">
            <?php echo $this -> stall -> short_description?>
        </div>
    </div>
</div>

<div class="socialcommerce-profile-fields">
    <div class="socialcommerce-overview-title socialcommerce-overview-line">
        <span class="socialcommerce-overview-toggle-button"><i class="ynicon yn-arr-down"></i></span>
        <span class="socialcommerce-overview-title-content"><i class="ynicon yn-text-align-justify"></i><?php echo $this->translate('Description');?></span>
    </div>
    <div class="socialcommerce-overview-content">
        <div class="socialcommerce-description rich_content_body">
            <?php echo $this -> stall -> description?>
        </div>
    </div>
</div>

<div class="socialcommerce-profile-fields">
    <div class="socialcommerce-overview-title socialcommerce-overview-line">
        <span class="socialcommerce-overview-toggle-button"><i class="ynicon yn-arr-down"></i></span>
        <span class="socialcommerce-overview-title-content"><i class="ynicon yn-money-bag"></i><?php echo $this->translate('Price Range');?></span>
    </div>
    <div class="socialcommerce-overview-content">
        <div class="socialcommerce-description rich_content_body">
            $10 - $100
        </div>
    </div>
</div>

<div class="socialcommerce-profile-fields">
    <div class="socialcommerce-overview-title socialcommerce-overview-line">
        <span class="socialcommerce-overview-toggle-button"><i class="ynicon yn-arr-down"></i></span>
        <span class="socialcommerce-overview-title-content"><i class="ynicon yn-envelope-o"></i><?php echo $this->translate('Email');?></span>
    </div>
    <div class="socialcommerce-overview-content">
        <div class="socialcommerce-description rich_content_body">
            <?php echo $this -> stall -> email?>
        </div>
    </div>
</div>

<div class="socialcommerce-profile-fields">
    <div class="socialcommerce-overview-title socialcommerce-overview-line">
        <span class="socialcommerce-overview-toggle-button"><i class="ynicon yn-arr-down"></i></span>
        <span class="socialcommerce-overview-title-content"><i class="ynicon yn-global"></i><?php echo $this->translate('Website');?></span>
    </div>
    <div class="socialcommerce-overview-content">
        <div class="socialcommerce-description rich_content_body">
            <a target="_blank" href="<?php echo $this -> stall -> web_address ?>"><?php echo $this -> stall -> web_address?></a>
        </div>
    </div>
</div>

<script type="text/javascript">
    $$('.socialcommerce-overview-toggle-button').addEvent('click', function(){
        this.toggleClass('socialcommerce-overview-content-closed');
        this.getParent('.socialcommerce-profile-fields').getElement('.socialcommerce-overview-content').toggle();
    });
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