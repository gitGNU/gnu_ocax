<?php

$this->menu=array(
	array('label'=>'List Años', 'url'=>array('adminYears')),
);

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'budget-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php if(!$model->isNewRecord){
		echo '<div class="row">';
		echo $form->labelEx($model,'year');
		$yearStr = ($model->year) .' - '. ($model->year + 1);
		echo '<input type="text" value="'.$yearStr.'" disabled />';
		echo '</div>';
	}else{
		echo '<div class="row">';
		echo $form->labelEx($model,'year');
		echo '<div class="hint">YYYY Solo cuatro dígitos</div>';
		echo $form->textField($model,'year');
		echo $form->error($model,'year');
		echo '</div>';
	 }?>

	<div class="row">
		<?php echo $form->labelEx($model,'provision'); ?>
		<div class="hint">Cifra sin puntos y comas</div>
		<?php echo $form->textField($model,'provision'); ?>
		<?php echo $form->error($model,'provision'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'spent'); ?>
		<div class="hint">Cifra sin puntos y comas</div>
		<?php echo $form->textField($model,'spent'); ?>
		<?php echo $form->error($model,'spent'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
