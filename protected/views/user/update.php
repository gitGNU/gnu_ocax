<?php
/* @var $this UserController */
/* @var $model User */

?>
<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<h1>Cambiar tus datos de usuario</h1>



<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

<div class="outer">
<div class="left">

	<p style="font-size:1.5em">Tus datos</p>

	<?php /*echo $form->errorSummary($model);*/ ?>

	<div class="row">
		<?php echo $form->labelEx($model,'fullname'); ?>
		<?php echo $form->textField($model,'fullname',array('size'=>30,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'fullname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>30,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'is_socio'); ?>
		<?php echo $form->checkBox($model,'is_socio', array('checked'=>$model->is_socio)); ?>
		¿eres socio?
	</div>

</div>
<div class="right">

<p style="font-size:1.5em">Cambiar contraseña</p>

	<div class="row">
		<?php echo $form->labelEx($model,'new_password'); ?>
		<?php echo $form->passwordField($model,'new_password'); ?>
		<?php echo $form->error($model,'new_password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password_repeat'); ?>
		<?php echo $form->passwordField($model,'password_repeat'); ?>
		<?php echo $form->error($model,'password_repeat'); ?>
	</div>

</div>
</div>
<div class="clear"></div>

	<div class="row buttons" style="text-align:center">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		<input type="button" onclick="window.location='<?php echo Yii::app()->request->baseUrl;?>/user/panel'" value="Cancel" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
