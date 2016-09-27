<?php
  // Render the admin js
  echo $this->render('_jsAdmin.tpl')
?>
<h2><?php echo $this->translate("Listings Plugin") ?></h2>
<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<div class="admin_fields_type">
  <p><?php echo $this->translate("SOCIALCOMMERCE_ADMIN_CATEGORY_FIELD_DESCRIPTION") ?></p>
  <br />
	<div>
		<?php foreach($this->category->getBreadCrumNode() as $node): ?>
			<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'socialcommerce', 'controller' => 'categories', 'action' => 'index'), $this->translate($node->shortTitle()), array()) ?>
		&raquo;
		<?php endforeach; ?>
		<strong><?php
            if(count($this->category->getBreadCrumNode()) > 0):
				echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'socialcommerce', 'controller' => 'categories', 'action' => 'index', 'parent_id' =>$this->category->category_id), $this->translate($this->category->shortTitle()), array());
			else:
				echo  $this->translate("All Categories");
			endif; ?></strong>
	</div>
	<br />
	<h3><?php echo $this->translate("Editing Custom Fields for Category:") ?>
		<?php echo $this->category -> getTitle(); ?>
	</h3>
</div>

<br />
<?php
	if($this -> category -> use_parent_category == '1')
	{
		$isChecked = true;
	}
	else {
		$isChecked = false;
	}
	if($this -> category->parent_id == '1'){
		$isShow = false;
	}
	else{
		$isShow = true;
	}
?>
<?php if($isShow) :?>
	<input id='use_parent_category' <?php echo ($isChecked)?  "checked = 'true'" : '' ?>" type='checkbox' value='<?php echo $this->categoryId ?>'><?php echo $this->translate('Use custom fields from parent category');?>
	<br /><br />
<?php endif;?>

<div class="admin_fields_options">
  <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addquestion"><?php echo $this->translate("Add Question") ?></a>
  <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addheading">Add Heading</a>
  <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_saveorder" style="display:none;">Save Order</a>
</div>

<br />

<ul class="admin_fields">
  <?php foreach( $this->secondLevelMaps as $map ): ?>
    <?php echo $this->adminFieldMeta($map) ?>
  <?php endforeach; ?>
</ul>

<br />
<br />

<?php if($isShow) :?>
	<script type="text/javascript">
	   window.addEvent('load', function() {
	      $('use_parent_category').addEvent('click', function(event) {
	      var isChecked = $('use_parent_category').getProperty('checked');
	      var check = 0;
	      if(isChecked)
	      {
	      	 check = 1;
	      }
	       var url = "<?php echo
	     'http://' . $_SERVER['HTTP_HOST']
						. Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
						  'module' => 'socialcommerce',
						  'controller' => 'categories',
				          'action' => 'ajax-use-parent-category',
				          'id' => $this->category->category_id,
				        ), 'admin_default', true);
			?>";
			url += "/check/" + check;
		      new Request.JSON({
					method: 'post',
					url: url,
			  }).send();
	      });
	    });
	</script>
<?php endif;?>