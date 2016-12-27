
<style type="text/css">
    .socialcommerce_manage_requests_table td:nth-of-type(1):before {content: "<?php echo $this->translate('Request time')?>";}
    .socialcommerce_manage_requests_table td:nth-of-type(2):before {content: "<?php echo $this->translate('Amount')?>";}
    .socialcommerce_manage_requests_table td:nth-of-type(3):before {content: "<?php echo $this->translate('Request message')?>";}
    .socialcommerce_manage_requests_table td:nth-of-type(4):before {content: "<?php echo $this->translate('Status')?>";}
    .socialcommerce_manage_requests_table td:nth-of-type(5):before {content: "<?php echo $this->translate('Status')?>";}
    .socialcommerce_manage_requests_table td:nth-of-type(6):before {content: "<?php echo $this->translate('Response message')?>";}
</style>

<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()->appendStylesheet($baseUrl . 'application/modules/Socialcommerce/externals/styles/ui-redmond/jquery-ui-1.8.18.custom.css');

$this->headScript()
->appendFile($baseUrl .'application/modules/Socialcommerce/externals/scripts/jquery-1.10.2.min.js')
->appendFile($baseUrl .'application/modules/Socialcommerce/externals/scripts/jquery-ui-1.11.4.min.js')

?>

<?php
$viewer = Engine_Api::_()->user()->getViewer();
$commission = 5;//Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('socialcommerce_deal', $viewer, 'commission');
if ($commission == "") {
$mtable = Engine_Api::_()->getDbtable('permissions', 'authorization');
$maselect = $mtable->select()
->where("type = 'socialcommerce_deal'")
->where("level_id = ?", $viewer->level_id)
->where("name = 'commission'");
$mallow_a = $mtable->fetchRow($maselect);
if (!empty($mallow_a))
$commission = $mallow_a['value'];
else
$commission = 0;
}
$this->headScript()
->appendFile($this->baseUrl() . '/application/modules/Socialcommerce/externals/scripts/socialcommere_function.js');
?>
<?php
$account = Socialcommerce_Api_Cart::getFinanceAccount($viewer->getIdentity());
if ($account['currency']):
$currency = $account['currency'];
else:
$currency = Engine_Api::_() -> getApi('settings', 'core')->getSetting('payment.currency', 'USD');
endif;
//$virtual = Engine_Api::_()->getApi('settings', 'core')->getSetting('socialcommerce.virtualmoney', 0);
if (!$account):
?>
<div class="tip" style="clear: inherit;">
<span>
  <?php echo $this->translate('You do not have any finance account yet. '); ?>
  <a href="<?php echo $this->url(array('action'=>'create'),'socialcommerce_account');  ?>"><?php echo $this->translate('Click here'); ?></a> <?php echo $this->translate('  to add your account.'); ?>
</span>
    <div style="clear: both;"></div>
</div>
<?php else: ?>
<div class="socialcommerce_my_account">
    <div>
        <div class="socialcommerce_my_account_price_block">
            <ul class="yn-clearfix">
                <li>
                    <div class="yn-clearfix">
                        <div>
                            <?php echo $this->locale()->toCurrency($this->total_sold, $currency) ?>
                        </div>
                        <div>
                            <div><?php echo $this->translate('Total sold') ?></div>
                            <div class="socialcommerce_account_limit"><?php echo $this->translate('Total listings/products sold corresponding to order statistic including offline payments') ?></div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="yn-clearfix">
                        <div>
                            <?php echo $this->locale()->toCurrency($this->total_commission, $currency) ?>
                        </div>
                        <div>
                            <div><?php echo $this->translate('Total commission') ?></div>
                            <div class="socialcommerce_account_limit"><?php echo $this->translate('Total commission for all sold listings/products.') ?></div>
                            <div><?php echo $this->translate('Commission fee').': '.round($commission, 2).'%' ?></div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="yn-clearfix">
                        <div>
                            <?php echo $this->locale()->toCurrency($this->current_amount, $currency) ?>
                        </div>
                        <div>
                            <div><?php echo $this->translate('Available amount') ?></div>
                            <div class="socialcommerce_account_limit"><?php echo $this->translate('Total availabel amount you can request to get real money') ?></div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="yn-clearfix">
                        <div>
                            <?php echo $this->locale()->toCurrency($this->waiting_amount, $currency) ?>
                        </div>
                        <div>
                            <div><?php echo $this->translate('Waiting amount') ?></div>
                            <div class="socialcommerce_account_limit"><?php echo $this->translate('Total amount you have requested to exchange to real money that is waiting for approval') ?></div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="yn-clearfix">
                        <div>
                            <?php echo $this->locale()->toCurrency($this->received_amount, $currency) ?>
                        </div>
                        <div>
                            <div><?php echo $this->translate('Received amount') ?></div>
                            <div class="socialcommerce_account_limit"><?php echo $this->translate('Total real money you have received') ?></div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="socialcommerce_my_account_request_block">
            <div class="socialcommerce_my_account_request_title yn-clearfix">
                <span>
                  <span><?php echo $this->translate('Manage requests') ?></span>
                  <span><?php echo '('.$this->translate(array('%s request', '%s requests', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())).')' ?></span>
                </span>
                <a class="smoothbox" href="<?php echo $this->url(array('user_id' => '1'), 'socialcommerce_payment_threshold') ?>" title="<?php echo $this->translate('Request'); ?>" ><button  name="request"><?php echo '<i class="ynicon yn-plus-thin"></i>'.' '.$this->translate('Make new request'); ?></button></a>
            </div>
            <div class="socialcommerce_my_account_request_search">
                <?php echo $this->filter_form->render($this) ?>
            </div>
        </div>
        <div class="socialcommerce_manage_requests_table_parent">
            <table class="socialcommerce_manage_requests_table">
                <thead>
                <tr>
                    <th><?php echo $this->translate('Request time') ?></th>
                    <th><?php echo $this->translate('Amount') ?></th>
                    <th><?php echo $this->translate('Request message') ?></th>
                    <th><?php echo $this->translate('Response time') ?></th>
                    <th><?php echo $this->translate('Status') ?></th>
                    <th><?php echo $this->translate('Response message') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                <?php foreach($this->paginator as $request): $count++; ?>
                <tr>
                    <td>
                        <?php
                  $oDate = new DateTime($request->request_date);
                        $oDate->setTimezone(new DateTimeZone($this->viewer->timezone));
                        echo $oDate->format("H:ia d/m/Y")
                        ?>
                    </td>
                    <td><?php echo $request->request_amount ?></td>
                    <td><a href="<?php echo $this->url(array('controller'=>'account', 'action'=>'load-message', 'request_id'=>$request->getIdentity(), 'type'=>'request'), 'socialcommerce_account') ?>" class="smoothbox"><?php echo $this->translate('View message') ?></a></td>
                    <td>
                        <?php
                  if($request->response_date != null){
                        $oDate = new DateTime($request->response_date);
                        $oDate->setTimezone(new DateTimeZone($this->viewer->timezone));
                        echo $oDate->format("H:ia d/m/Y");
                        }
                        ?>
                    </td>
                    <td><?php echo $request->request_status; ?></td>
                    <td>
                        <?php if($request->response_message): ?>
                        <a href="<?php echo $this->url(array('controller'=>'account', 'action'=>'load-message', 'request_id'=>$request->getIdentity(), 'type'=>'response'), 'socialcommerce_account') ?>" class="smoothbox"><?php echo $this->translate('View message') ?></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php echo $this->paginationControl($this->paginator); ?>
        </div>
    </div>
</div>



<script>
    jQuery(document).ready(function(){
        jQuery('#request_from').datepicker({
            firstDay: 1,
            showOn: "button",
            buttonImageOnly: true,
            buttonText: '',
            dateFormat: 'yy-mm-dd'
        });

        jQuery('#request_to').datepicker({
            firstDay: 1,
            showOn: "button",
            buttonImageOnly: true,
            buttonText: '<?php echo $this -> translate("Select date")?>',
            dateFormat: 'yy-mm-dd'
        });

        jQuery('#response_from').datepicker({
            firstDay: 1,
            showOn: "button",
            buttonImageOnly: true,
            buttonText: '',
            dateFormat: 'yy-mm-dd'
        });

        jQuery('#response_to').datepicker({
            firstDay: 1,
            showOn: "button",
            buttonImageOnly: true,
            buttonText: '<?php echo $this -> translate("Select date")?>',
            dateFormat: 'yy-mm-dd'
        });
    });
</script>

<?php endif ?>