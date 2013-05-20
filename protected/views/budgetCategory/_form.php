<?php
/* @var $this BudgetCategoryController */
/* @var $model BudgetCategory */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'budget-category-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title"><?php echo $title;?></div>

	<?php /* echo $form->errorSummary($model); */ ?>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? __('Create') : __('Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
