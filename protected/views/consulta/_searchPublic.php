<?php
/* @var $this ConsultaController */
/* @var $model Consulta */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'type'); ?>
		<?php echo $form->dropDownList($model, 'type', array(""=>"Sin filtrar") + $model->humanTypeValues);?>
	</div>

	<div class="row">
		<?php /*echo $form->label($model,'capitulo'); */ ?>
		<?php /* echo $form->textField($model,'capitulo'); */ ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php echo $form->dropDownList($model, 'state', array(""=>"Sin filtrar") + $model->humanStateValues);?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>20,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'body'); ?>
		<?php echo $form->textField($model,'body',array('size'=>20,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
