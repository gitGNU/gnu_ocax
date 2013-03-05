<?php
/* @var $this EmailController */
/* @var $data Email */
?>

<div class="view">

	<p>
	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<?php
		if($data->sent)
			echo '<span style="color:green">Sent OK</span>';
		else
			echo '<span style="color:red">Failed</span>';
	?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sender')); ?>:</b>
	<?php echo CHtml::encode($data->sender0->fullname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('recipients')); ?>:</b>
	<?php echo CHtml::encode($data->recipients); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	</p>

	<?php echo $data->body; ?>
	<br />

</div>
