<?php
$this->pageTitle=Yii::app()->name . ' - Register';
$this->breadcrumbs=array(
 'Register',
);
?>
 
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
 'id'=>'register-form',
 'enableClientValidation'=>true,
 'clientOptions'=>array(
 'validateOnSubmit'=>true,
 ),
)); ?>
 
<div class="title">Register</div>

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
 <div class="hint">Please enter the letters as they are shown in the image above.
 <br/>Letters are not case-sensitive.</div>
 <?php echo $form->error($model,'verifyCode'); ?>
 </div>
 <?php endif; ?>
 <div class="row buttons">
 <?php echo CHtml::submitButton('Register'); ?>
 </div>
 
<?php $this->endWidget(); ?>
</div><!-- form -->
