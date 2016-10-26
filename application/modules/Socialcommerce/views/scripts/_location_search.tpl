<div id="location-wrapper" class="form-wrapper">
    <div id="location-label" class="form-label">
        <label><?php echo $this->translate("Location") ?></label>
    </div>
    <div id="location-element" class="form-element">
        <!--<a style="right: 20px; position: absolute;" class='socialcommerce_location_icon' href="javascript:void()" onclick="return getCurrentLocation(this);" >
            <img style="margin-top: 6px;" src="application/modules/Socialcommerce/externals/images/icon-search-advform.png" alt="">
        </a> -->
        <input type="text" name="location" id="location" value="<?php if($this->location) echo $this->location;?>">
    </div>
</div>

