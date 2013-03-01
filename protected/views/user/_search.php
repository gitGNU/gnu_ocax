<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fullname'); ?>
		<?php echo $form->textField($model,'fullname',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'joined'); ?>
		<?php echo $form->textField($model,'joined'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_active'); ?>
		<?php echo $form->textField($model,'is_active'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_socio'); ?>
		<?php echo $form->textField($model,'is_socio'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_team_member'); ?>
		<?php echo $form->textField($model,'is_team_member'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_editor'); ?>
		<?php echo $form->textField($model,'is_editor'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_manager'); ?>
		<?php echo $form->textField($model,'is_manager'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
