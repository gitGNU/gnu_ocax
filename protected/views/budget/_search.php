<?php
/* @var $this BudgetController */
/* @var $model Budget */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">

		<?php echo $form->hiddenField($model,'year'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'concept'); ?>
		<?php echo $form->textField($model,'concept',array('size'=>40,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'provision'); ?>
		<?php echo $form->textField($model,'provision'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'spent'); ?>
		<?php echo $form->textField($model,'spent'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
