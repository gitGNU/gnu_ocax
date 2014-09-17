<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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

/* @var $this BudgetDescriptionController */
/* @var $model BudgetDescription */
/* @var $form CActiveForm */

if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

	/*
	if(Yii::app()->clientScript->isScriptRegistered('jquery.js'))
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	if(Yii::app()->clientScript->isScriptRegistered('jquery.min.js'))
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
	*/
}
if(!isset($budget_id))
	$budget_id = Null;

?>

<script>
function submitDescription()
{
	$.ajax({
		type: 'POST',
		url: '<?php echo ($model->isNewRecord) ?
					Yii::app()->createAbsoluteUrl('budgetDescription/create?budget='.$budget_id) :
					Yii::app()->createAbsoluteUrl('budgetDescription/update/'.$model->id) ?>',
		data: $("#budget-description-form").serialize(),
		//beforeSend: function(){ tinyMCE.triggerSave(); },
		complete: function() {
					if(typeof budgetDetailsUpdated == 'function')
						budgetDetailsUpdated();	//this function is in budget/index
		},
		success: function(result){	},
		error: function() {
			alert("Error budgetDescription/update");
		},
	});
}
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'budget-description-form',
	'enableAjaxValidation'=>false,
));

	if($model->description)
		$title = __('Change the budget description');
	else
		$title = __('Create a budget description');

	if(Yii::app()->request->isAjaxRequest)
		echo '<div class="modalTitle">'.$title.'</div>';
	else
		echo '<div class="title">'.$title.'</div>';
?>

<?php /*if($model->isNewRecord){*/ ?>

	<?php echo $form->errorSummary($model); ?>

<div>

	<div class="row left" style="width:55%">
		<?php echo $form->labelEx($model,'csv_id'); ?>
		<?php echo $form->textField($model,'csv_id',array('style'=>'width:350px','maxlength'=>100,'disabled'=>1)); ?>
		<?php echo $form->error($model,'csv_id'); ?>
	</div>

<?php /*}else{ ?>
	<p>
	<?php $this->widget('zii.widgets.CDetailView', array(
		'cssFile' => Yii::app()->request->baseUrl.'/css/pdetailview.css',
		'data'=>$model,
		'attributes'=>array(
			'csv_id',
			'language',
			array(
				'label'=>__('Used where'),
				'type'=>'raw',
				'value'=>$model->whereUsed(),
			),
		),
	)); */?>
	</p>
<?php /*}*/ ?>

	<div class="row left" style="width:160px">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('style'=>'width:125px','maxlength'=>32,'disabled'=>1)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<?php
		if($listData = getLanguagesArray()){
			echo '<div class="row left" style="width:170px">';
			echo $form->labelEx($model,'language');
			//echo '<div class="hint">'.__('Description language').'</div>';
			echo $form->dropDownList($model, 'language', $listData, array('prompt'=>__('Select a language')));
			echo $form->error($model,'language');
			echo '</div>';
		}else{
			echo $form->hiddenField($model,'language');
		}
	?>

<div class="clear"></div>
	<div class="row left" style="width:220px">
		<?php echo $form->labelEx($model,'label'); ?>
		<div class="hint"><?php echo __('Concept, Subconcept, Article').'..';?></div>
		<?php echo $form->textField($model,'label',array('style'=>'width:200px','maxlength'=>255)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>

	<div class="row left" style="width:505px">
		<?php echo $form->labelEx($model,'concept'); ?>
		<div class="hint"><?php echo __('Concept of this budget');?></div>
		<?php echo $form->textField($model,'concept',array('style'=>'width:500px','maxlength'=>255)); ?>
		<?php echo $form->error($model,'concept'); ?>
	</div>
</div>
<div class="clear"></div>

<?php
$settings=array('convert_urls'=>true,
				'relative_urls'=>false,
				'remove_script_host'=>false,
				//'entity_encoding' => "raw",
				'theme_advanced_resize_horizontal' => 0,
				'theme_advanced_resize_vertical' => 0,
				'theme_advanced_resizing_use_cookie' => false,
				'width'=>'100%',
				'height' => 350,
				'valid_elements' => "@[style],p,span,a[href|target=_blank],strong/b,div[align],br,ul,ol,li",
				'theme_advanced_buttons1' => "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,
												justifyright,|,bullist,numlist,|,outdent,indent,|,
												undo,redo,|,link,unlink,|,image,|,code",
			);
if(Config::model()->findByPk('HTMLeditorUseCompressor'))
	$settings['useCompression']=true;
else
	$settings['useCompression']=false;

if(Yii::app()->request->isAjaxRequest){
	$settings['useCompression']=false;
	$tinyMCEAssets = Yii::app()->getAssetManager()->getPublishedUrl(Yii::app()->basePath.'/extensions/tinymce/vendors/tinymce/jscripts/tiny_mce');
	echo '<script>tinyMCE.baseURL = "'.$tinyMCEAssets.'"</script>';
}

$init = array(
    'model' => $model,
    'attribute' => 'description',
    // Optional config
    'compressorRoute' => 'tinyMce/compressor',
    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',
	'settings' => $settings,
);

if(!Config::model()->findByPk('HTMLeditorUseCompressor') || Yii::app()->request->isAjaxRequest)
	unset($init['compressorRoute']);

echo '<div class="row">';
	echo $form->labelEx($model,'description');
	$this->widget('ext.tinymce.TinyMce', $init);
	echo $form->error($model,'description');
echo '</div>';
?>

<?php if(Yii::app()->request->isAjaxRequest){ ?>
	<div class="row buttons">
		<?php  /* echo CHtml::submitButton($model->description ? 'ajax Create' : 'ajax Save'); */ ?>
		<?php  echo CHtml::Button(__('Make changes'), array('onclick'=>'js:submitDescription();'));  ?>
	</div>
<?php } else { ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->description ? 'Create' : 'Save'); ?>
	</div>
<? } ?>

<?php $this->endWidget(); ?>

</div><!-- form -->
