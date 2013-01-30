<?php
/* @var $this CmspageController */
/* @var $data CmsPage */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('block')); ?>:</b>
	<?php echo CHtml::encode($data->block); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pagename')); ?>:</b>
	<?php echo CHtml::encode($data->pagename); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pageTitle')); ?>:</b>
	<?php echo CHtml::encode($data->pageTitle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('body')); ?>:</b>
	<?php echo CHtml::encode($data->body); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weight')); ?>:</b>
	<?php echo CHtml::encode($data->weight); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('published')); ?>:</b>
	<?php echo CHtml::encode($data->published); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('heading')); ?>:</b>
	<?php echo CHtml::encode($data->heading); ?>
	<br />





	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('metaTitle')); ?>:</b>
	<?php echo CHtml::encode($data->metaTitle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('metaDescription')); ?>:</b>
	<?php echo CHtml::encode($data->metaDescription); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('metaKeywords')); ?>:</b>
	<?php echo CHtml::encode($data->metaKeywords); ?>
	<br />

	*/ ?>

</div>
