<?php
/* @var $this ConfigController */
/* @var $data Config */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('parameter')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->parameter), array('view', 'id'=>$data->parameter)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('value')); ?>:</b>
	<?php echo CHtml::encode($data->value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />


</div>