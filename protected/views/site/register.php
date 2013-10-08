<?php
$this->pageTitle=Yii::app()->name . ' - Register';
$this->breadcrumbs=array(
 'Register',
);
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
#RegisterForm_verifyCode { display: block; }
.outer{width:100%; padding: 0px; float: left;}
.left{width: 48%; float: left;  margin: 0px;}
.right{width: 48%; float: left; margin: 0px;}
.clear{clear:both;}
</style>

<div class="outer">

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
 'id'=>'register-form',
 'enableClientValidation'=>true,
 'clientOptions'=>array(
 'validateOnSubmit'=>true,
 ),
)); ?>

<div class="left">


 
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
 


</div>
<div class="right">

<?php echo __('REGISTER_MSG'); ?>

<div style="border:solid 1px;">
<p>
Ser socio sólo implica apoyar todas y cada una de las enquirys ciudadanas.
Es más "simbólico" que práctico legal. Me explico, todas las enquirys/instancias que
se envíen en el Ayuntamiento llevan la firma y el NIF del Observatorio Ciudadano,
si el Obsevatorio en cuestión tiene 2000 socios, de forma simbólica implica que hay 2000 firmas ciudadanas detrás.
De todas formas, legalmente una instancia "vale lo mismo" y tiene el mismo valor si está firmada por 1 o 1000 personas.
</p>
<p>
Yes, I want to be a socio. <?php echo $form->checkBox($model,'is_socio'); ?>
</p>
</div>


</div>

<?php $this->endWidget(); ?>
</div><!-- form -->


</div>
