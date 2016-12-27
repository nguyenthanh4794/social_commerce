
<div class="socialcommerce_sub_mail_parent" style="box-sizing: border-box;">
    <div class="socialcommerce_sub_mail" style=" border: 2px solid #ddd; padding: 28px;box-sizing: border-box;">
        <!-- title -->
        <div class="socialcommerce_sub_mail_company_name" style="box-sizing: border-box;font-size: 18px; font-weight: bold; line-height: 22px; color: #555; margin-top: -5px; border-bottom: 1px solid #ddd; padding-bottom: 24px;"><?php echo $this->site_name ?></div>
        <!-- content -->
        <div class="socialcommerce_sub_mail_main_content" style="box-sizing: border-box;">
            <div class="socialcommerce_sub_mail_text_content" style="box-sizing: border-box;">
                <div class="socialcommerce_sub_mail_top_content" style="box-sizing: border-box;font-weight: bold; font-size: 14px; line-height: 16px;text-align: center; color: #555; margin-top: 22px; margin-bottom: 12px;">You have just purchase from our website with below order:</div>
                <div class="socialcommerce_sub_mail_bottom_content" style="box-sizing: border-box;font-size:14px;font-weight:normal;line-height:16px;text-align:center;color:#555;"></div>
            </div>
        </div>
        <!-- info -->
        <ul class="socialcommerce_sub_mail_items" style="margin: 0;margin-top: 23px;box-sizing: border-box;padding-left: 0;">
            <?php foreach($this->products as $orderItem): ?>
            <?php $item = $orderItem->getObject(); ?>
            <li class="yn-clearfix" style="margin-bottom: 20px;list-style: none;box-sizing: border-box;">
                <div class="socialcommerce_sub_mail_main_info" style="box-sizing: border-box;">
                    <!-- photo -->
                    <div class="socialcommerce_sub_mail_photo" style="box-sizing: border-box;width:280px;height:180px;float:left;display:inline-block;">
                        <a href="#" style="background-image: url(<?php echo $this->uri . $item->getPhotoUrl() ?>);box-sizing: border-box;width:100%;height:100%;display:block;background-origin:border-box;background-position:center center;background-size:contain;background-repeat:no-repeat;border:1px solid rgba(0,0,0,0.1);"></a> <!-- TODO add href-->
                    </div>
                    <!-- info -->
                    <div class="socialcommerce_sub_mail_info" style="box-sizing: border-box;overflow:hidden;height:180px;border:1px solid #e5e5e5;border-left:0;padding:15px;">
                        <a class="socialcommerce_sub_mail_info_title" style="margin-bottom: 4px;color: #5f93b4;box-sizing: border-box;margin-top: -2px;font-size:16px;font-weight:bold;line-height:18px;margin-top:-2px;overflow:hidden;white-space:nowrap;word-break:break-word;word-wrap:break-word;text-overflow:ellipsis;display:block;"><?php echo $item->getTitle() ?></a>
                        <?php if($item->category): ?>
                        <div class="socialcommerce_sub_mail_info_category" style="box-sizing: border-box;overflow:hidden;white-space:nowrap;word-break:break-word;word-wrap:break-word;text-overflow:ellipsis;display:block;font-size:12px;line-height:12px;color:#999;">
                            Category <span style="text-decoration: none;color: #5f93b4;"><?php echo $item->getCategory()->getTitle(); ?></span>
                        </div>
                        <?php endif ?>
                        <div class="socialcommerce_sub_mail_decs" style="margin-top: 8px;box-sizing: border-box;font-size:13px;margin-bottom:9px;color:#555;word-break:break-word;word-wrap:break-word;overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-box-orient:vertical;line-height:16px;-webkit-line-clamp:2;max-height:32px;font-weight:normal;">
                            <?php $item->getDescription() ?>
                        </div>
                        <div class="socialcommerce_sub_mail_subinfo yn-clearfix" style="box-sizing: border-box;border-top: 1px solid #eee;height: 65px;padding-top: 9px;">
                            <div class="socialcommerce_sub_mail_price" style="box-sizing: border-box;display: inline-block;float: left;border-right: 1px solid #ebebeb;padding-right: 20px;margin-right: 20px;">
                                <div class="socialcommerce_sub_mail_current_price" style="box-sizing: border-box;font-size: 18px;line-height: 22px;font-weight: bold;color: #555;margin-bottom: 2px;"><?php echo $item->price.$orderItem->currency ?></div>
                                <div style="display: flex;display: -moz-flex;display: -webkit-flex;">
                                    <div class="socialcommerce_sub_mail_original_price" style="box-sizing: border-box;font-size: 18px;font-weight: normal;line-height: 18px;color: #555;"><?php echo 'x'.$orderItem->quantity ?></div>
                                </div>
                            </div>

                            <?php $oDate = new DateTime($item->creation_date); ?>

                            <div class="socialcommerce_sub_mail_time" style="box-sizing: border-box;display: inline-block;float: left;">
                                <div style="box-sizing: border-box;color: #888;font-size: 14px;line-height: 18px;font-weight: normal;">Creation Date</div>
                                <div style="box-sizing: border-box;font-size: 18px;line-height: 22px;font-weight: bold;color: #555;"><?php echo $oDate->format("d/m/Y").' '.$oDate->format("H:i A"); ?></div>
                            </div>
                            <button style="box-sizing: border-box;display: inline-block;text-shadow: 1px 1px 0px rgba(0, 0, 0, .3);background-color: #619dbe;border: 1px solid #50809b;-moz-border-radius: 3px;border-radius: 3px;-webkit-border-radius: 3px;float: right;margin-top: 5px;transition: all 0.3s;-moz-transition: all 0.3s;-webkit-transition: all 0.3s;"><a href="<?php echo $this->uri . $item->getHref() ?>" style="color: #5f93b4;box-sizing: border-box;text-decoration: none;text-transform: uppercase;font-size: 14px;line-height: 18px;font-weight: bold;color: #fff;padding: .5em .8em;display: inline-block;">view detail</a></button>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <ul class="socialcommerce_sub_mail_option yn-clearfix" style="box-sizing: border-box;margin-top: 11px;list-style: none;padding-left: 0;">
        <li style="line-height: 18px;margin-left: 0;display: inline-block;box-sizing: border-box;"><a href="<?php echo $this->uri ?>" style="color: #5f93b4;box-sizing: border-box;">Visit website</a></li>
    </ul>
</div>



<style type="text/css">
    br{display: none; !important}
    body{background-color: red}
</style>

