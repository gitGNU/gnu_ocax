<?php
/* @var $this BackupController */
/* @var $model Backup */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vault'); ?>
		<?php echo $form->textField($model,'vault'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'filename'); ?>
		<?php echo $form->textField($model,'filename',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'initiated'); ?>
		<?php echo $form->textField($model,'initiated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'completed'); ?>
		<?php echo $form->textField($model,'completed'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'checksum'); ?>
		<?php echo $form->textField($model,'checksum',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php echo $form->textField($model,'state'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->