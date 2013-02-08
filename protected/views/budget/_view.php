<?php
/* @var $this BudgetController */
/* @var $data Budget */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent')); ?>:</b>
	<?php echo CHtml::encode($data->parent); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year')); ?>:</b>
	<?php echo CHtml::encode($data->year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('concept')); ?>:</b>
	<?php echo CHtml::encode($data->concept); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('provision')); ?>:</b>
	<?php echo CHtml::encode($data->provision); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('spent')); ?>:</b>
	<?php echo CHtml::encode($data->spent); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('weight')); ?>:</b>
	<?php echo CHtml::encode($data->weight); ?>
	<br />

	*/ ?>

</div>