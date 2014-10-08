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

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'budget-description-form',
	'enableAjaxValidation'=>false,
));

	if($model->description)
		$title = __('Change the budget description');
	else
		$title = __('Create a budget description');

	echo '<div class="title">'.$title.'</div>';
?>
<?php echo '<p style="margin:0px;">'.__('Used where').' '.$model->whereUsed().'</p>'; ?>

<div>
	<div class="row left" style="width:350px">
		<?php echo $form->labelEx($model,'csv_id'); ?>
		<?php echo $form->textField($model,'csv_id',array('style'=>'width:330px','disabled'=>1)); ?>
		<?php echo $form->error($model,'csv_id'); ?>
	</div>

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
<!-- above this ok -->

<?php
$settings=array('convert_urls'=>true,
				'relative_urls'=>false,
				'remove_script_host'=>false,
				//'entity_encoding' => "raw",
				'theme_advanced_resize_horizontal' => 0,
				'theme_advanced_resize_vertical' => 0,
				'theme_advanced_resizing_use_cookie' => false,
				'width'=>'100%',
				'height' => 300,
				'valid_elements' => "@[style],p,span,a[href|target=_blank],strong/b,div[align],br,ul,ol,li",
				'theme_advanced_buttons1' => "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,
												justifyright,|,bullist,numlist,|,outdent,indent,|,
												undo,redo,|,link,unlink,|,image,|,code",
			);
if(Config::model()->findByPk('HTMLeditorUseCompressor'))
	$settings['useCompression']=true;
else
	$settings['useCompression']=false;

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

if(!Config::model()->findByPk('HTMLeditorUseCompressor')->value)
	unset($init['compressorRoute']);

echo '<div class="row">';
	echo $form->labelEx($model,'description');
	$this->widget('ext.tinymce.TinyMce', $init);
	echo $form->error($model,'description');
echo '</div>';
?>

<div class="row buttons">
	<?php echo CHtml::submitButton(__('Update the description')); ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
