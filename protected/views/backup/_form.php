<?php
/* @var $this BackupController */
/* @var $model Backup */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'backup-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'vault'); ?>
		<?php echo $form->textField($model,'vault'); ?>
		<?php echo $form->error($model,'vault'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'filename'); ?>
		<?php echo $form->textField($model,'filename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'filename'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'initiated'); ?>
		<?php echo $form->textField($model,'initiated'); ?>
		<?php echo $form->error($model,'initiated'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'completed'); ?>
		<?php echo $form->textField($model,'completed'); ?>
		<?php echo $form->error($model,'completed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'checksum'); ?>
		<?php echo $form->textField($model,'checksum',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'checksum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->textField($model,'state'); ?>
		<?php echo $form->error($model,'state'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->