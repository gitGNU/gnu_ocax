<?php
/* @var $this EmailController */
/* @var $data Email */
?>

<div class="view">

	<p>
	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sender')); ?>:</b>
	<?php echo CHtml::encode($data->sender0->fullname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('recipients')); ?>:</b>
	<?php echo CHtml::encode($data->recipients); ?>
	</p>

	<?php echo $data->body; ?>
	<br />


</div>
