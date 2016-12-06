<?php $photoCount = $this->paginator->getTotalItemCount() ?>
<div class="socialcommerce_manage_photo_title">
    <?php echo $this->htmlLink(array('route' => 'socialcommerce_general', 'action' => 'manage-selling'), $this->translate('Manage Listings')) ?>
    <?php echo ' / '.$this->listing ?>
    <span><?php echo ' / '.$this->translate('Photos');?></span>
</div>
<div class="socialcommerce_manage_photo_addmore">
    <span><?php echo $this->translate(array('%s photo', '%s photos', $photoCount), $this->locale()->toNumber($photoCount)) ?></span>
    <a href="<?php echo $this->url(array('controller'=>'photo', 'action'=>'upload', 'listing_id'=>$this->listing->getIdentity()), 'socialcommerce_extended') ?>"><button><i class="ynicon yn-photo-plus-o"></i><?php echo $this->translate('Add more photos') ?></button></a>
</div>
<?php if($this->paginator->getTotalItemCount()): ?>
<form class="global_form" action="<?php echo $this->escape($this->form->getAction()) ?>" method="<?php echo $this->escape($this->form->getMethod()) ?>">
    <input type="hidden" name="listing_id" value="<?php echo $this->listing->getIdentity() ?>">
    <div class="form-elements socialcommerce_manage_photo_form">
        <ul class='socialcommerce_editphotos yn-clearfix'>
            <?php foreach( $this->paginator as $photo ): ?>
            <li>
                <div class="socialcommerce_editphotos_parent">
                    <div class="socialcommerce_editphotos_photo">
                        <span style="background-image: url('<?php echo $photo->getPhotoUrl('thumb.profile')  ?>')";></span>
                    </div>
                    <div class="socialcommerce_editphotos_info">
                        <?php
                                $key = $photo->getGuid();
                        echo $this->form->getSubForm($key)->render($this);
                        ?>
                        <div class="socialcommerce_editphotos_cover">
                            <input type="radio" name="cover" id="<?php echo $photo->getIdentity() ?>" value="<?php echo $photo->getIdentity() ?>" <?php if( $this->deal->photo_id == $photo->file_id ): ?> checked="checked"<?php endif; ?> />
                            <label for="<?php echo $photo->getIdentity() ?>" ><?php echo $this->translate('Main Photo');?></label>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php echo $this->form->submit->render(); ?>
        <?php echo $this->form->cancel; ?>
    </div>
</form>
<?php else: ?>
<?php endif; ?>

