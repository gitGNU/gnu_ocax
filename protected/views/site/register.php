<?php
$this->pageTitle=Config::model()->findByPk('siglas')->value . ' - '.__('Register');
?>

<script>
$("input[type='checkbox']").each(function(){
	if(!$(this).is(':checked')) $(this).val("0");
	$(this).click(function(){
		if($(this).is(':checked'))
			$(this).val("1");
		else
			$(this).val("0");
	});                                                               
});
</script>


<style>
.outer{width:100%; padding: 0px; float: left;}
.left{width: 48%; float: left;  margin: 0px;}
.right{width: 48%; float: left; margin: 0px;}
.clear{clear:both;}
</style>

<div class="outer">
<h1><?php echo __('Register');?></h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
 'id'=>'register-form',
 'enableClientValidation'=>true,
 'clientOptions'=>array(
 'validateOnSubmit'=>true,
 ),
)); ?>

<div class="left">

<div style="width:100%">
	<div class="row" style="width:38%; float:left;">
	<?php echo $form->labelEx($model,'username'); ?>
	<?php echo $form->textField($model,'username',array('style'=>'width:140px')); ?>
	<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row" style="width:60%; float:left;">
	<?php echo $form->labelEx($model,'fullname'); ?>
	<?php echo $form->textField($model,'fullname',array('style'=>'width:250px')); ?>
	<?php echo $form->error($model,'fullname'); ?>
	</div>
</div>

<div style="width:100%">
	<div class="row" style="width:38%; float:left;">
	</div>
	<div class="row" style="width:38%; float:left;">
	<?php echo $form->labelEx($model,'email'); ?>
	<?php echo $form->textField($model,'email',array('style'=>'width:250px')); ?>
	<?php echo $form->error($model,'email'); ?>
	</div>
</div>

<div style="width:100%">
	<div class="row" style="width:38%; float:left;">
	<?php echo $form->labelEx($model,'password'); ?>
	<?php echo $form->passwordField($model,'password',array('style'=>'width:140px')); ?>
	<?php echo $form->error($model,'password'); ?>
	</div>
 
	<div class="row" style="width:38%; float:left;">
	<?php echo $form->labelEx($model,'password_repeat'); ?>
	<?php echo $form->passwordField($model,'password_repeat',array('style'=>'width:140px')); ?>
	<?php echo $form->error($model,'password_repeat'); ?>
	</div>
</div>
<div class="clear"></div>

<style>
#RegisterForm_verifyCode, #yw0_button { display: block; }
#yw0 {
	border:2px solid #ccc;
	-webkit-border-radius: 4px;
	border-radius: 4px;
}
</style>

<div class="row" style="margin-top:20px">
<?php if(CCaptcha::checkRequirements()): ?>
 
	<?php echo $form->labelEx($model,'verifyCode'); ?>
	<div>
	<?php $this->widget('CCaptcha'); ?>
	<?php echo $form->textField($model,'verifyCode',array('style'=>'width:116px')); ?>
	</div>

	<div class="hint" style="margin-top:-5px"><?php echo __('Please enter the letters as they are shown in the image above');?>.</div>
	<?php echo $form->error($model,'verifyCode'); ?>
	</div>
<?php endif; ?>
	<div class="row buttons" style="margin-top:20px">
	<?php echo CHtml::submitButton(__('Register')); ?>
	</div>
</div>

</div>
<div class="right">

<?php echo __('REGISTER_MSG'); ?>

<?php if(Config::model()->findByPk('membership')->value){ ?>
<div class="horizontalRule"></div>
<p><?php echo __('MEMBERSHIP_MSG'); ?></p>
<p>
<?php
	echo __('Yes, I want to be a member').' ';
	echo $form->checkBox($model,'is_socio');
	echo '<label for="RegisterForm_is_socio"></label>';
	
?>
</p>
<?php }else $model->is_socio = 0; ?>

</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
</div>
