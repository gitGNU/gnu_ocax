<?php
/* @var $this ConfigController */
/* @var $model Config */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'config-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">


		<?php echo $form->labelEx($model,'parameter'); ?>
		<?php
		if(!$model->isNewRecord){
			echo $form->hiddenField($model,'parameter');			
			echo '<input type="text" value="'.$model->parameter.'" size="60" disabled />';
		}else
			echo $form->textField($model,'parameter',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'parameter'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<?php
		if(!$model->isNewRecord){
			echo '<div class="row">';
			echo $form->labelEx($model,'can_delete');
			echo $form->textField($model,'can_delete',array('size'=>60,'maxlength'=>255));
			echo $form->error($model,'can_delete');
			echo '</div>';
		}
	?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
