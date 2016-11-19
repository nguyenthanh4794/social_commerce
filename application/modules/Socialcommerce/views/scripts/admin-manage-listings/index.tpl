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

<div class="socialcommerce_manage_form_admin_search clearfix">
    <?php echo $this->form->render($this);?>
</div>
<?php if (count($this->paginator) > 0): ?>
    <form id="socialcommerce_manage_form_table" method="post" action="<?php echo $this->url(); ?>">
    <table class='admin_table' id>
        <thead>
        <tr>
            <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox'/></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'DESC');"><?php echo $this->
                    translate("Product") ?></a></th>
            <th><?php echo $this->translate("Owner") ?></th>
            <th><?php echo $this->translate("Stall") ?></th>
            <th><?php echo $this->translate("Category") ?></th>
            <th><a href="javascript:void(0);"
                   onclick="javascript:changeOrder('creation_date', 'DESC');"><?php echo $this->translate("Created
                    Date") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('status', 'DESC');"><?php echo $this->
                    translate("Listing Status") ?></a></th>
            <th><a href="javascript:void(0);"
                   onclick="javascript:changeOrder('approve_status', 'DESC');"><?php echo $this->translate("Approve
                    Status") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('featured', 'DESC');"><?php echo $this->
                    translate("Featured") ?></a></th>
            <th><?php echo $this->translate("Options") ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->paginator as $item): ?>
        <tr>
            <td class="checksub"><input type='checkbox' class='checkbox' name='item_<?php echo $item->getIdentity(); ?>'
                                        value="<?php echo $item->product_id ?>"/></td>
            <td><a href="<?php echo $item->getHref()?>"><?php echo $item->getTitle() ?></a></td>
            <td><?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?></td>
            <td> <?php $stall = Engine_Api::_()->getItem('socialcommerce_stall', $item->stall_id); ?>
                <a href="<?php echo $stall ? $stall->getHref() : ''?>"><?php echo $stall ? $stall->getTitle() : ''
                    ?></a>
            </td>
            <td><?php echo $item->getCategory(); ?></td>
            <td><?php
            $create_date = $this->locale()->toDateTime($item->creation_date);
                echo date("d F Y", strtotime($create_date))?>
            </td>
            <td>
                <span><?php echo $item->status ?></span>
            </td>
            <td>
                <span><?php echo $item->approve_status ?></span>
            </td>
            <td>
                <?php if($item->featured == 1): ?>
                <input type="checkbox" id='featureproduct_<?php echo $item->product_id; ?>'
                       onclick="featureProduct(<?php echo $item->product_id; ?>,this)" checked/>
                <?php else: ?>
                <input type="checkbox" id='featureproduct_<?php echo $item->product_id; ?>'
                       onclick="featureProduct(<?php echo $item->product_id; ?>,this)"/>
                <?php endif; ?>
            </td>

            <td>
                <div>
                    <div>
                        <?php echo $this->htmlLink($item->getHref(), $this->translate('view')) ?>
                    </div>
                    <div>
                        <?php echo $this->htmlLink(array(
                        'module' => 'socialcommerce',
                        'controller' => 'manage-listings',
                        'action' => 'edit-product',
                        'product_id' => $item->getIdentity(),
                        'route' => 'admin_default',
                        'reset' => true,
                        ), $this->translate('edit'), array(
                        'class' => ' ',
                        )) ?>
                    </div>
                    <div>
                        <?php echo $this->htmlLink(array(
                        'module' => 'socialcommerce',
                        'controller' => 'manage-listings',
                        'action' => 'delete-product',
                        'product_id' => $item->getIdentity(),
                        'route' => 'admin_default',
                        'reset' => true,
                        ), $this->translate('delete'), array(
                        'class' => ' smoothbox ',
                        )) ?>
                    </div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <button type='button' name="submit" value='delete' id="delete" onclick="multiDelete()">
        <?php echo $this->translate("Delete Selected") ?>
    </button>
    <button type='submit' name="submit" value='approve' id="approve">
        <?php echo $this->translate("Approve Selected") ?>
    </button>
    <button type='submit' name="submit" value='deny' id="deny">
        <?php echo $this->translate("Deny Selected") ?>
    </button>
    <button type='submit' name="submit" value='feature' id="feature">
        <?php echo $this->translate("Feature Selected") ?>
    </button>
    <button type='submit' name="submit" value='unfeature' id="unfeature">
        <?php echo $this->translate("Unfeature Selected") ?>
    </button>
</form>
    <br/>

    <div>
        <?php
            echo $this->paginationControl($this->paginator, null, null, array(
        'pageAsQuery' => true,
        'query' => $this->params,
        ));
        ?>
    </div>
<?php else: ?>
    <div class="tip">
        <span>
            <?php echo $this->translate("There are no listings available.") ?>
        </span>
    </div>
<?php endif; ?>

<script type="text/javascript">
    function featureProduct(product_id, checbox) {
        if (checbox.checked == true) status = 1;
        else status = 0;
        new Request.JSON({
            'format': 'json',
            'url': '<?php echo $this->url(array('module' => 'socialcommerce', 'controller' => 'manage-listings', 'action' => 'featured'), 'admin_default') ?>',
            'data': {
                'format': 'json',
                'product': product_id,
                'good': status
            }
        }).send();
    }

    function selectAll() {
        var i;
        var multidelete_form = $('socialcommerce_manage_form_table');
        var inputs = multidelete_form.elements;
        for (i = 1; i < inputs.length; i++) {
            if (!inputs[i].disabled) {
                if ($(inputs[i]).hasClass('checkbox')) {
                    inputs[i].checked = inputs[0].checked;
                }
            }
        }
    }
    var currentOrder = '<?php echo $this->filterValues['order'] ?>';
    var currentOrderDirection = '<?php echo $this->filterValues['direction'] ?>';
    var changeOrder = function (order, default_direction) {
        // Just change direction
        if (order == currentOrder) {
            $('direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
        } else {
            $('order').value = order;
            $('direction').value = default_direction;
        }
        $('filter_form').submit();
    }
    var delectSelected = function () {
        var checkboxes = $$('td.checksub input[type=checkbox]');
        var selecteditems = [];
        $$("td.checksub input[type=checkbox]:checked").each(function (i) {
            selecteditems.push(i.value);
        });
        $('ids').value = selecteditems;
        $('delete_selected').submit();
    }
    var approveSelected = function () {
        var checkboxes = $$('td.checksub input[type=checkbox]');
        var selecteditems = [];
        $$("td.checksub input[type=checkbox]:checked").each(function (i) {
            selecteditems.push(i.value);
        });

        $('ids1').value = selecteditems;
        $('approve_selected').submit();
    }

    function multiDelete() {
        Smoothbox.open('<?php echo $this->url(array('module' => 'socialcommerce', 'controller' => 'manage-listings', 'action' => 'multi-delete-confirm'), 'admin_default', true); ?>');
        return false;
    }

    function submitForm() {
        $('socialcommerce_manage_form_table').submit();
    }
</script>