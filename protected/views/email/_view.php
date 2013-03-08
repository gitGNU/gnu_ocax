<?php
/* @var $this EmailController */
/* @var $data Email */
?>

<div class="view">
	<div style="padding:10px;margin:-10px;background-color:#CAE1FF;margin-bottom:5px;font-size:1.3em;">
		<b>Subject</b>
		<?php echo CHtml::encode($data->title); ?>
	</div>

	<div style="padding:10px;margin:-10px;background-color:#F0F8FF;margin-bottom:15px;">
	<p style="margin-bottom:10px">
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
	<?php
		if($data->sender)
			echo CHtml::encode($data->sender0->fullname);
		else
			echo 'Automatic email';
	?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sent_as')); ?>:</b>
	<?php echo CHtml::encode($data->sent_as); ?>
	</p>

	<b><?php echo CHtml::encode($data->getAttributeLabel('recipients')); ?>:</b>
	<?php echo CHtml::encode($data->recipients); ?>

	</div>

	<?php echo $data->body; ?>
	<br />

</div>
