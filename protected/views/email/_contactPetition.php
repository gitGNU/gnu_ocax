<?php
/* @var $this EmailController */
/* @var $model Email */
/* @var $form CActiveForm */

?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'email-form',
	//'enableAjaxValidation'=>true,
	//'enableClientValidation'=>false,
	//'action'=>Yii::app()->baseUrl.'/email/create',
)); ?>

	<div class="title"><?php echo __('Petition to make contact via email')?></div>

	<?php
		echo $form->hiddenField($model,'enquiry');
		$model->recipients=$recipient->email;
		echo $form->hiddenField($model,'recipients');

		$user = User::model()->findByPk(Yii::app()->user->getUserID());

		$block = Yii::app()->createAbsoluteUrl('user/block/'.$user->username);
		$block = '<a href="'.$block.'">'.$block.'</a>';

		$model->title=	'<p>'.__('Hello').' '.$recipient->fullname.',</p><p>'.
						$user->fullname.', '.__('a user like you at the').' '.Config::model()->findByPk('siglas')->value.', '.
						__('would like to contact you privately via email').'.<br />'.
						__('However, we do not share users\' email addresses').'.</p><p>'.
						__('If you wish, you may make contact yourself with').' '.$user->fullname.'; '.$user->email.'</p><p>'.
						__('If you think this user is spamming you, you can block future petitions at this link').'<br />'.
						$block.'</p>'.
						__('Kind regards').',<br />'.Config::model()->findByPk('observatoryName')->value;

		echo '<div style="	background-color:white;
							margin:-10px;
							margin-top:-15px;
							margin-bottom:0px;
							border-bottom: 1px solid #C9E0ED;
							padding:5px">'.$model->title.'</div>';		
		echo $form->hiddenField($model,'title');

		echo '<div class="row">';
		echo '<b>'.$user->fullname.' '.__('says').'...</b><br />';
		echo '<div class="hint">'.__('Optionally, you may attach text to this email').'</div>';
		echo $form->textArea($model, 'body', array('style'=>'width: 100%; height: 120px;'));
		echo '</div>';

	?>


	<div id="contact_petition_buttons" class="row buttons">
		<input type="button" value="<?php echo __('Send email')?>" onClick="js:sendContactForm('email-form');"/>
		<input type="button" value="<?php echo __('Cancel')?>" onClick="js:$('#contact_petition').bPopup().close()" />
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<style>
.contact_form_bottom{
	display:none;
	margin:-11px;
	margin-top:0px;
	padding:15px;
	border-top: 1px solid #C9E0ED;
	text-align:center;
	font-size:1.4em;
	background-color:white;
}
</style>
<div id="contact_petition_sending" class="contact_form_bottom" style="color:orange">
<?php echo __('Sending email')?>&nbsp;&nbsp;
<?php echo '<img style="vertical-align:text-middle;"
			src="'.Yii::app()->theme->baseUrl.'/images/loading.gif" />'?>
</div>
<div id="contact_petition_sent" class="contact_form_bottom" style="color:green">
<?php echo __('Email sent')?>
</div>
<div id="contact_petition_error" class="contact_form_bottom" style="color:red">
</div>


