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

<div class="admin_fields_options">
	<a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addquestion">Add Question</a>
	<a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addheading">Add Heading</a>
</div>

<br />


<ul class="admin_fields">
	<?php foreach( $this->secondLevelMaps as $map ): ?>
	<?php echo $this->adminFieldMeta($map) ?>
	<?php endforeach; ?>
</ul>

<br />
<br />