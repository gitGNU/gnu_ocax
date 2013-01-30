<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fullname')); ?>:</b>
	<?php echo CHtml::encode($data->fullname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('joined')); ?>:</b>
	<?php echo CHtml::encode($data->joined); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_socio')); ?>:</b>
	<?php echo CHtml::encode($data->is_socio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_team_member')); ?>:</b>
	<?php echo CHtml::encode($data->is_team_member); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_editor')); ?>:</b>
	<?php echo CHtml::encode($data->is_editor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_manager')); ?>:</b>
	<?php echo CHtml::encode($data->is_manager); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_admin')); ?>:</b>
	<?php echo CHtml::encode($data->is_admin); ?>
	<br />

</div>
