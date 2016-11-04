<?php if(isset($this->hasAccount) && !$this->hasAccount): ?>
<?php $url = $this -> url(array(
'module' => 'socialcommerce',
'controller' => 'seller',
'action' => 'create',
'format' => 'smoothbox'),'default', true);?>
<div class="tip">
    <span><?php echo $this->translate("You don't have seller account. Please ") ?><a href="javascript:void(0);" onclick="Smoothbox.open('<?php echo $url?>')"><?php echo $this->translate('create one') ?></a></span>
</div>
<?php else: ?>


<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()->appendStylesheet($baseUrl . 'application/modules/Socialcommerce/externals/styles/ui-redmond/jquery-ui-1.8.18.custom.css');

$this->headScript()
->appendFile($baseUrl .'application/modules/Socialcommerce/externals/scripts/jquery-1.6.1.min.js')
->appendFile($baseUrl .'application/modules/Socialcommerce/externals/scripts/jquery-ui-1.11.4.min.js');
?>


<script type="text/javascript">
    jQuery(document).ready(function(){
        var current = new Date();
        var yearRange = (current.getFullYear() - 100) +':' + (current.getFullYear() + 10);
        jQuery.noConflict();
        jQuery('#start_date').datepicker({
            firstDay: 1,
            dateFormat: 'yy-mm-dd',
            showOn: "button",
            buttonImage: '',
            changeMonth: true,
            changeYear: true,
            yearRange: yearRange,
            buttonText: '',
        });

        jQuery('#to_date').datepicker({
            firstDay: 1,
            dateFormat: 'yy-mm-dd',
            showOn: "button",
            buttonImage: '',
            changeMonth: true,
            changeYear: true,
            yearRange: yearRange,
            buttonText: '',
        });

    });
    function selectAll() {
        var i;
        var multidelete_form = $('yndform_manage_form_table');
        var inputs = multidelete_form.elements;
        for (i = 1; i < inputs.length; i++) {
            if (!inputs[i].disabled) {
                if ($(inputs[i]).hasClass('checkbox')) {
                    inputs[i].checked = inputs[2].checked;
                }
            }
        }
    }
</script>

<div class="clearfix">
    <?php echo $this->form->render($this);?>
</div>
<form id='yndform_manage_form_table' method="post" action="<?php echo $this->url(); ?>" onSubmit="return multiDelete()">
    <table class='admin_table'>
        <thead>
        <tr>
            <th class='admin_table_short'>
                <input onclick='selectAll();' type='checkbox' class='checkbox' />
            </th>
            <th field="title">
                <a href="javascript:void(0);" onclick="changeOrder('title', this)">
                    <?php echo $this->translate("Title") ?>
                </a>
            </th>
            <th field="category">
                <a href="javascript:void(0);" onclick="changeOrder('category', this)">
                    <?php echo $this->translate("Category") ?>
                </a>
            </th>
            <th field="creation_date">
                <a href="javascript:void(0);" onclick="changeOrder('creation_date', this)">
                    <?php echo $this->translate("Creation Date") ?>
                </a>
            </th>
            <th field="enable">
                <a href="javascript:void(0);" onclick="changeOrder('enable', this)">
                    <?php echo $this->translate("Status") ?>
                </a>
            </th>
            <th field="view_count">
                <a href="javascript:void(0);" onclick="changeOrder('view_count', this)">
                    <?php echo $this->translate("Views") ?>
                </a>
            </th>
            <th field="total_entries">
                <a href="javascript:void(0);" onclick="changeOrder('total_entries', this)">
                    <?php echo $this->translate("Entries") ?>
                </a>
            </th>
            <th field="conversation_rate">
                <a href="javascript:void(0);" onclick="changeOrder('conversation_rate', this)">
                    <?php echo $this->translate("Conversion Rate") ?>
                </a>
            </th>
            <th><?php echo $this->translate("Options") ?></th>
        </tr>
        </thead>

        <tbody>
            <tr>
                <td>abc</td>
                <td>abc</td>
                <td>abc</td>
                <td>abc</td>
                <td>abc</td>
                <td>abc</td>
                <td>abc</td>
                <td>abc</td>
            </tr>
        </tbody>
    </table>

    <button type='submit' value='delete' id="delete">
        <?php echo $this->translate("Delete Selected") ?>
    </button>
</form>

<?php endif; ?>