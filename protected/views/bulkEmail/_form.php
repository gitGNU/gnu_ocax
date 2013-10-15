<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/* @var $this BulkEmailController */
/* @var $model BulkEmail */

?>

<style>
#recipients_link{
	cursor:pointer;
	text-decoration:underline;
}
</style>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script>
function showRecipients(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/bulkEmail/showRecipients',
		type: 'GET',
		success: function(data){
			if(data != 0){
				$("#recipients_body").html(data);
				$('#recipients').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show recipients");
		}
	});
}
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bulk-email-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title"><?php echo __('Send bulk email')?></div>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php $model->sent_as=Config::model()->findByPk('emailNoReply')->value;?>
		<?php echo '<p><b>'.__('Sent as').':</b> '.$model->sent_as.'</p>'; ?>
		<?php echo $form->hiddenField($model,'sent_as'); ?>
	</div>

	<div class="row">
		<p>
		<b><?php echo $total_recipients.' '.__('BCC Recipients');?></b>: <span id="recipients_link" onClick="js:showRecipients();">Show</span>
		</p>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'subject'); ?>
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
		<?php echo CHtml::submitButton(__('Preview')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<div id="recipients" style="width:600px;">
	<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
	<div id="recipients_body"></div>
</div>

