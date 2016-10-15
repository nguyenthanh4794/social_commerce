<div id="location-wrapper" class="form-wrapper">
    <div id="location-label" class="form-label">
        <?php echo $this->translate("Location") ?>
    </div>
    <div id="location-element" class="form-element">
        <input type="text" name="location" id="location" value="<?php if($this->location) echo $this->location;?>">
        <a class='ynlistings_location_icon' href="javascript:void()" onclick="return getCurrentLocation(this);" >
            <img src="application/modules/Socialcommerce/externals/images/icon-search-advform.png" alt="">
        </a>
    </div>
</div>

