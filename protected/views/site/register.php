<?php
$this->pageTitle=Yii::app()->name . ' - Register';
$this->breadcrumbs=array(
 'Register',
);
?>

<style>
#RegisterForm_verifyCode { display: block; }
.outer{width:100%; padding: 0px; float: left;}
.left{width: 48%; float: left;  margin: 0px;}
.right{width: 48%; float: left; margin: 0px;}
.clear{clear:both;}
</style>

<div class="outer">
<div class="left">

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
 'id'=>'register-form',
 'enableClientValidation'=>true,
 'clientOptions'=>array(
 'validateOnSubmit'=>true,
 ),
)); ?>
 
<div class="title"><?php echo __('Register');?></div>

<div class="row">
 <?php echo $form->labelEx($model,'username'); ?>
 <?php echo $form->textField($model,'username'); ?>
 <?php echo $form->error($model,'username'); ?>
 </div>

<div class="row">
 <?php echo $form->labelEx($model,'fullname'); ?>
 <?php echo $form->textField($model,'fullname'); ?>
 <?php echo $form->error($model,'fullname'); ?>
 </div>
 
 <div class="row">
 <?php echo $form->labelEx($model,'email'); ?>
 <?php echo $form->textField($model,'email'); ?>
 <?php echo $form->error($model,'email'); ?>
 </div>
 
<div class="row">
 <?php echo $form->labelEx($model,'password'); ?>
 <?php echo $form->passwordField($model,'password'); ?>
 <?php echo $form->error($model,'password'); ?>
 </div>
 
 <div class="row">
 <?php echo $form->labelEx($model,'password_repeat'); ?>
 <?php echo $form->passwordField($model,'password_repeat'); ?>
 <?php echo $form->error($model,'password_repeat'); ?>
 </div>
 
<?php if(CCaptcha::checkRequirements()): ?>
 <div class="row">
 <?php echo $form->labelEx($model,'verifyCode'); ?>
 <div>
 <?php $this->widget('CCaptcha'); ?>
 <?php echo $form->textField($model,'verifyCode'); ?>
 </div>
 <div class="hint"><?php echo __('Please enter the letters as they are shown in the image above');?>.</div>
 <?php echo $form->error($model,'verifyCode'); ?>
 </div>
 <?php endif; ?>
 <div class="row buttons">
 <?php echo CHtml::submitButton(__('Register')); ?>
 </div>
 
<?php $this->endWidget(); ?>
</div><!-- form -->

</div>
<div class="right">

<p>Porque registrarse</p>
<p>Porque registrarse</p>
<p>Porque registrarse</p>
<p>Porque registrarse</p>
<p>Porque registrarse</p>
<p>Porque registrarse</p>
<p>Porque registrarse</p>
<p>Porque registrarse</p>
<p>Porque registrarse</p>
<p>Porque registrarse</p>

</div>
</div>
