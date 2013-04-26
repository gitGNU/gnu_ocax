<?php
/* @var $this EmailController */
/* @var $model Email */
/* @var $form CActiveForm */

if($returnURL == 'enquiry/teamView'){
	$this->menu=array(
		array('label'=>__('View enquiry'), 'url'=>array('/enquiry/teamView', 'id'=>$enquiry->id)),
		array('label'=>__('Update state'), 'url'=>array('/enquiry/update', 'id'=>$enquiry->id)),
		array('label'=>__('Edit enquiry'), 'url'=>array('/enquiry/edit', 'id'=>$enquiry->id)),
		array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$enquiry->id, 'menu'=>'team')),
		array('label'=>__('List enquiries'), 'url'=>array('/enquiry/managed')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
}
if($returnURL == 'enquiry/adminView'){
	$this->menu=array(
		//array('label'=>'View Enquiry', 'url'=>array('/enquiry/adminView', 'id'=>$enquiry->id)),
		//array('label'=>'Actualizar estat', 'url'=>array('/enquiry/update', 'id'=>$enquiry->id)),
		//array('label'=>'Editar Enquiry', 'url'=>array('/enquiry/edit', 'id'=>$enquiry->id)),
		array('label'=>'Emails sent', 'url'=>array('/email/index/', 'id'=>$enquiry->id, 'menu'=>'manager')),
		array('label'=>'List enquirys', 'url'=>array('/enquiry/admin')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
}
?>

<style>
#recipients_link{
	cursor:pointer;
	text-decoration:underline;
}
</style>

<script>
function toggleRecipients(){
	if ($('#recipients').is (':visible'))
		$('#recipients_link').html('Show');
	else
		$('#recipients_link').html('Hide');
	$('#recipients').toggle();
}
</script>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'email-form',
	//'enableAjaxValidation'=>true,
	//'enableClientValidation'=>false,
	'action'=>Yii::app()->baseUrl.'/email/create',
)); ?>

	<div class="title"><?php echo __('Send email')?></div>

	<?php echo $form->hiddenField($model,'enquiry'); ?>
	<input type="hidden" name="Email[returnURL]" value="<?php echo $returnURL;?>" />

	<div class="row">
		<?php

		$sender=User::model()->findByPk($model->sender);
		$senderList=array(	0=>Config::model()->findByPk('emailNoReply')->value,
							$sender->id=>$sender->email);
		$model->sender=0;
		?>
		<?php echo $form->labelEx($model,'sender'); ?>
		<?php echo $form->dropDownList($model, 'sender', $senderList );?>
		<?php echo $form->error($model,'sender'); ?>
	</div>


	<div class="row">
		<?php /*echo $form->labelEx($model,'recipients');*/ ?>
		<?php
			$criteria = array(
				'with'=>array('enquirySubscribes'),
				'condition'=>' enquirySubscribes.enquiry = '.$enquiry->id,
				'together'=>true,
			);
			$subscribedUsers = User::model()->findAll($criteria);
			$model->recipients='';
			foreach($subscribedUsers as $subscribed)
				$model->recipients=$model->recipients.' '.$subscribed->email.',';
			$model->recipients = substr_replace($model->recipients ,"",-1);
			echo $form->hiddenField($model,'recipients');
			
			echo '<p><b>'.count($subscribedUsers).' BCC Recipients</b> <span id="recipients_link" onClick="js:toggleRecipients();">Show</span>';
			echo '<div id="recipients" style="background-color:white;padding:4px;display:none">'.$model->recipients.'</div>';
		?>
		</p>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php
		$this->widget('ext.tinymce.TinyMce', array(
		    'model' => $model,
		    'attribute' => 'body',
		    // Optional config
		    'compressorRoute' => 'tinyMce/compressor',
		    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
		    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
		    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',
			'settings' => array('convert_urls'=>true,
								'relative_urls'=>false,
								'remove_script_host'=>false
								),
		    'htmlOptions' => array(
		        'rows' => 6,
		        'cols' => 80,
		    ),
		));
		?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Enviar' : 'Save'); ?>
		<input type="button" value="Cancel" onclick='js:window.location="<?php echo Yii::app()->baseUrl.'/'.$returnURL.'/'.$enquiry->id;?>";' />

	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<p></p>
<?php echo $this->renderPartial('//enquiry/_teamView', array('model'=>$enquiry,'replys'=>$replys)); ?>


