<h2><?php echo $this->translate("Social Commerce Plugin") ?></h2>
<?php if( count($this->navigation) ): ?>
<div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
</div>
<?php endif; ?>

<p>
    <?php echo $this->translate("SOCIALCOMMERCE_VIEWS_SCRIPTS_STATISTIC_INDEX_DESCRIPTION") ?>
</p>

<div class='clear'>
    <div class='settings'>
        <form class="global_form">
            <div>
                <h3><?php echo $this->translate("Social Commerce Statistics") ?></h3>

                <p><?php echo $this->translate("SOCIALCOMMERCE_VIEWS_SCRIPTS_STATISTIC_INDEX_DESCRIPTION") ?></p>
                <br/>
                <div class="profile_fields">
                    <h3><span><?php echo $this->translate('Store Statistic');?></span></h3>
                    <ul>
                        <li>
                            <span><?php echo $this->translate("Total Stalls") ?></span>
                            <span>
                                <?php echo $this->locale()->toNumber($this->totalStalls); ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Featured Stalls") ?></span>
                            <span>
                                <?php echo $this->locale()->toNumber($this->featuredStalls); ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Approved Stalls") ?></span>
                            <span>
				                <?php echo $this->locale()->toNumber($this->approvedStalls); ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Users Follow Stalls") ?></span>
                            <span>
				                <?php echo $this->locale()->toNumber($this->usersFollow); ?>
				            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Followed Stalls") ?></span>
                            <span>
				                <?php echo $this->locale()->toNumber($this->storesFollowed); ?>
				            </span>
                        </li>
                    </ul>
                    <h3><span><?php echo $this->translate('Product Statistic');?></span></h3>
                    <ul>
                        <li>
                            <span><?php echo $this->translate("Total Products") ?></span>
                            <span>
				                <?php echo $this->locale()->toNumber($this->totalProducts); ?>
				            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Featured Products") ?></span>
                            <span>
				                <?php echo $this->locale()->toNumber($this->featuredProducts); ?>
				            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Approved Products") ?></span>
                            <span>
                                <?php echo $this->locale()->toNumber($this->approvedProducts); ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Users Favourite Products") ?></span>
                            <span>
                                <?php echo $this->locale()->toNumber($this->usersFavourite); ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Favourited Products") ?></span>
                            <span>
				                <?php echo $this->locale()->toNumber($this->productsFavourited); ?>
				            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Total Units Sold") ?></span>
                            <span>
                                <?php echo $this->locale()->toNumber($this->soldProducts); ?>
                            </span>
                        </li>
                    </ul>
                    <h3><span><?php echo $this->translate('Finance Statistic');?></span></h3>
                    <ul>
                        <li>
                            <span><?php echo $this->translate("Total Publishing Fee from Stalls") ?></span>
                            <span>
                                <?php echo $this->currency($this->storesPublishFee) ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Total Feature Fee from Stalls") ?></span>
                            <span>
                                <?php echo $this->currency($this->storesFeaturedFee) ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Total Publishing and Feature Fee from Stalls") ?></span>
                            <span>
                                <?php echo $this->currency($this->storesFee) ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Total Publishing Fee from Products") ?></span>
                            <span>
                                <?php echo $this->currency($this->productsPublishFee) ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Total Feature Fee from Products") ?></span>
                            <span>
                                <?php echo $this->currency($this->productsFeaturedFee) ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->
                                translate("Total Publishing and Feature Fee from Products") ?></span>
                            <span>
                                <?php echo $this->currency($this->productsFee) ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Total Commission") ?></span>
                            <span>
                                <?php echo $this->currency($this->commission) ?>
                            </span>
                        </li>
                        <li>
                            <span><?php echo $this->translate("Total Income") ?></span>
                            <span>
                                <?php echo $this->currency($this->totalIncome) ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
</div>