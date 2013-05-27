<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */


?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<script>
function showPasswdInstructions(){
	$('#passwd_instructions_link').replaceWith($('#passwd_instructions'));
	$('#passwd_instructions').show();
}
function requestNewPasswd(){
	if($('#email').val() == ''){
		alert("<?php echo __('Please enter your email address');?>");
		return;
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/site/requestNewPassword',
		type: 'GET',
		async: false,
		data: { 'email': $('#email').val() },
		beforeSend: function(){ $('#loading').show(); $('#email_button').prop('disabled', true);  },
		success: function(data){
			$('#loading').hide();
			$('#email_button').prop('disabled', false); 
			$('#passwd_text').hide();
			$('#passwd_text').html(data);
			$('#passwd_text').fadeIn('fast');
		},
		error: function() {
			alert("Error on request new password");
		}
	});
}
</script>


<div class="outer">
<div class="left">

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="title"><?php echo __('Login')?></div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(__('Login')); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<p></p>

</div>
<div class="right">
<p style="font-size:1.5em;margin-bottom:10px;"><?php echo __('Still haven\'t got an account?');?></p>
<p>
	<a href="<?php echo Yii::app()->request->baseUrl; ?>/site/register"><?php echo __('Sign up');?></a>
</p>
<br/>
<p style="font-size:1.5em;margin-bottom:10px;"><?php echo __('Forgotten your password?');?></p>

<p>
<a id="passwd_instructions_link" class="link" onClick="js:showPasswdInstructions()"><?php echo __('Follow these instructions');?></a>
<div id="passwd_instructions" class="form" style="display:none">
<p id="passwd_text" style="height:20px;"><?php echo __('Enter your email address and we will send you a link');?></p>
<input id="email" type="text" style="margin-right:10px;" /><button id="email_button" onClick="js:requestNewPasswd();"><?php echo __('Send');?></button>
<img id="loading" src="<?php echo Yii::app()->theme->baseUrl;?>/images/small_loading.gif" style="vertical-align:middle;margin-left:10px;display:none"/>
</div>
</p>

</div>
</div>

<?php if(Yii::app()->user->hasFlash('error')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_error').fadeOut('fast');
    	}, 3500);
		});
	</script>
    <div class="flash_error">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('error');?></b></p>
    </div>
<?php endif; ?>


