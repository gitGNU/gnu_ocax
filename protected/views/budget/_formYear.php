<?php


?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'budget-form',
	'enableAjaxValidation'=>false,
)); ?>

<div class="title"><?php echo $title;?></div>

<div class="outer">
<div class="left">

	<?php /*echo $form->errorSummary($model); */?>

	<div class="row">
	<?php if($model->isNewRecord){
		echo $form->labelEx($model,'year');
		echo '<div class="hint">YYYY '.__('Only 4 digits').'</div>';
		echo $form->textField($model,'year');
		echo $form->error($model,'year');
	 }?>
	</div>

	<div class="row">
		<b><?php echo __('Population');?></b><br />
		<?php
			$model->initial_provision = substr_replace($model->initial_provision ,"",-3);	//don't want population to have decimals
			echo '<div class="hint">'.__('Population this year').'</div>';
			echo $form->textField($model,'initial_provision');
		?>
	</div>


</div>
<div class="right">

	<div class="row" style="font-size:1.4em">
		<?php echo $totalBudgets.' '.__('defined budgets');?>
	</div>

	<div class="row" style="margin-top:50px;">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->dropDownList($model, 'code', array('0'=>'Not published','1'=>'Published'));?>
		<?php echo $form->error($model,'code'); ?>
	</div>

</div>
</div>
<div class="clear"></div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
