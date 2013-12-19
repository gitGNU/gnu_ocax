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

/* @var $this EnquiryController */
/* @var $model Enquiry */
/* @var $form CActiveForm */

$user_id = Yii::app()->user->getUserID();
$user= User::model()->findByPk($user_id);
if(!$user->is_active)
	$this->renderPartial('//user/_notActiveInfo', array('model'=>$user));
?>

<script>
$(document).ready(function() {
	if(1 != <?php echo $user->is_active;?>){
		$('#enquiry-form').find(':input:not(:disabled)').prop('disabled',true);
		$('#enquiry-form').find(':textarea:not(:disabled)').prop('disabled',true);
	}
});
function submitForm(){
	$('.loading_gif').show();
	$('input[type=button]').prop("disabled",true);
	document.forms['enquiry-form'].submit();
}
</script>


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title">
	<?php
		if($model->isNewRecord){
			if($model->related_to)
				echo ' '.__('New reformulated enquiry');
		}else
			echo __('Modify enquiry');
	?>
	</div>
	<?php
		if(!$model->isNewRecord && Yii::app()->user->getUserID() == $model->team_member)
			$this->renderPartial('_detailsForTeam', array('model'=>$model));
		else{
			if($model->budget){
				echo '<div class="row" style="margin:0px 0px 0px -10px;">';
				$budget=Budget::model()->findByPk($model->budget);
				$this->renderPartial('//budget/_enquiryView',array('model'=>$budget,'showMore'=>1));
				echo '</div>';
			}
		}
	?>

	<?php echo $form->hiddenField($model,'budget'); ?>
	<?php echo $form->hiddenField($model,'related_to'); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'style'=>'width:100%')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>

<?php
$this->widget('ext.tinymce.TinyMce', array(
    'model' => $model,
    'attribute' => 'body',
    'compressorRoute' => 'tinyMce/compressor',
    
    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',
	'settings' => array('convert_urls'=>true,
						'relative_urls'=>false,
						'remove_script_host'=>false,
						//'entity_encoding' => "raw",
						'theme_advanced_resize_horizontal' => 0,
						'theme_advanced_resize_vertical' => 0,
						'theme_advanced_resizing_use_cookie' => false,
						'width'=>'100%',
						'valid_elements' => "@[style],p,span,a[href|target=_blank],strong/b,div[align],br,ul,ol,li",
						),
));
?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php $buttonText = $model->isNewRecord ? __('Publish') : __('Update') ?>
		<input type="button" onclick="submitForm()" value="<?php echo $buttonText; ?>">

		<?php	if (!$model->id)
					$cancelURL='/user/panel';
				elseif ($model->team_member == $user_id)	// remember: a team_memebr can edit a enquiry
					$cancelURL='/enquiry/teamView/'.$model->id;
				else
					$cancelURL='/enquiry/'.$model->id;
		?>
		<input type="button" value="<?php echo __('Cancel')?>" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />
		<img style="vertical-align:middle;display:none" class="loading_gif" src="<?php echo Yii::app()->request->baseUrl;?>/images/loading.gif" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


