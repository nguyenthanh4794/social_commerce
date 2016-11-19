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

<div class='clear'>
    <div class='settings'>
        <form class="global_form">
            <div>
                <h3><?php echo $this->translate("Social Commerce FAQs") ?></h3>

                <p><?php echo $this->translate("SOCIALCOMMERCE_ADMIN_FAQS_DESCRIPTION") ?></p>
                <br />

                <div>
                    <?php echo $this->htmlLink(array(
                    'route' => 'admin_default',
                    'module' => 'socialcommerce',
                    'controller' => 'faqs',
                    'action' => 'create'),
                    '<button>'.$this->translate('Add New FAQ').'</button>', array('class' => 'smoothbox',)) ?>
                </div>
                <br />

                <table class="admin_table" style="width: 100%;">
                    <thead>
                    <tr>
                        <th><?php echo $this->translate("Question") ?></th>

                        <th><?php echo $this->translate("Status") ?></th>

                        <th style = "text-align: right;"><?php echo $this->translate("Order") ?></th>



                        <th><?php echo $this->translate("Created") ?></th>

                        <th><?php echo $this->translate("Options") ?></th>
                    </tr>
                    </thead>
                    <tbody id="demo-list">
                    <?php foreach($this->paginator as $item) :?>
                    <tr id='faq_item_<?php echo $item->getIdentity() ?>'>

                        <td>
                            <?php echo $item->question ?>
                        </td>

                        <td>
                            <?php echo $item->status ? 'Show' : 'Hide' ?>
                        </td>

                        <td style = "text-align: right;">
                            <?php echo $item->ordering ?>
                        </td>

                        <td>
                            <?php echo $item->creation_date ?>
                        </td>
                        <td>
                            <?php echo $this->htmlLink(array(
                            'route' => 'admin_default',
                            'module' => 'socialcommerce',
                            'controller' => 'faqs',
                            'action' => 'edit',
                            'id' => $item->getIdentity()),
                            $this->translate('Edit'), array('class' => 'smoothbox',)) ?> |
                            <?php echo $this->htmlLink(array(
                            'route' => 'admin_default',
                            'module' => 'socialcommerce',
                            'controller' => 'faqs',
                            'action' => 'delete',
                            'id' => $item->getIdentity()),
                            $this->translate('Delete'), array('class' => 'smoothbox',)) ?>
                        </td>

                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Page Paginator -->
                <div>
                    <?php  echo $this->paginationControl($this->paginator, null, null, array(
                    'pageAsQuery' => false,
                    'query' => $this->formValues,
                    ));     ?>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    en4.core.runonce.add(function(){
        new Sortables('demo-list', {
            contrain: false,
            clone: true,
            handle: 'span',
            opacity: 0.5,
            revert: true,
            onComplete: function(){
                new Request.JSON({
                    url: '<?php echo $this->url(array('controller'=>'faqs','action'=>'sort'), 'admin_default') ?>',
                    noCache: true,
                    data: {
                        'format': 'json',
                        'order': this.serialize().toString(),
                }
            }).send();
            }
        });
    });
</script>