<?php
/* @var $this ConfigController */
/* @var $model Config */

$this->menu=array(
	array('label'=>'Listar parámetros', 'url'=>array('admin')),
);
?>

<h1>Change value of '<?php echo $model->parameter; ?>'</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'config-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'parameter'); ?>
		<?php echo $form->hiddenField($model,'parameter'); ?>
		<input type="text" value="<?php echo $model->parameter;?>" size="60" disabled />
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->hiddenField($model,'description'); ?>
		<input type="text" value="<?php echo $model->description;?>" size="60" disabled />
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
