<?php
/* @var $this EmailtextController */
/* @var $data Emailtext */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('state')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->state), array('view', 'id'=>$data->state)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('body')); ?>:</b>
	<?php echo CHtml::encode($data->body); ?>
	<br />


</div>