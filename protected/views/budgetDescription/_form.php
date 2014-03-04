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

/* @var $this BudgetDescriptionController */
/* @var $model BudgetDescription */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'budget-description-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title"><?php echo $title;?></div>

<?php if($model->isNewRecord){ ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'csv_id'); ?>
		<?php echo $form->textField($model,'csv_id',array('size'=>20,'maxlength'=>100,'disabled'=>1)); ?>
		<?php echo $form->error($model,'csv_id'); ?>
	</div>

	<?php
		if($listData = getLanguagesArray()){
			echo '<div class="row">';
			echo $form->labelEx($model,'language');
			//echo '<div class="hint">'.__('Description language').'</div>';
			echo $form->dropDownList($model, 'language', $listData, array('prompt'=>__('Select a language')));
			echo $form->error($model,'language');
			echo '</div>';
		}else{
			echo $form->hiddenField($model,'language');
		}	
	?>

<?php }else{ ?>
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
	)); ?>
	</p>
<?php } ?>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>20,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'label'); ?>
		<div class="hint"><?php echo __('Examples are Concept, Subconcept, Article, etc');?></div>
		<?php echo $form->textField($model,'label',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'concept'); ?>
		<?php echo $form->textField($model,'concept',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'concept'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
<?php
$this->widget('ext.tinymce.TinyMce', array(
    'model' => $model,
    'attribute' => 'description',
    // Optional config
    'compressorRoute' => 'tinyMce/compressor',
    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',
	'settings' => array(
						'theme_advanced_buttons1' => "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,
													justifyright,|,bullist,numlist,|,outdent,indent,|,
													undo,redo,|,link,unlink,|,image,|,code",
						'convert_urls'=>true,
						'relative_urls'=>false,
						'remove_script_host'=>false,
						'theme_advanced_resize_horizontal' => 0,
						'theme_advanced_resize_vertical' => 0,
						'theme_advanced_resizing_use_cookie' => false,
						'width'=>'100%',
						'valid_elements' => "@[style],p,span,a[href|target=_blank],strong/b,div[align],br,ul,ol,li,img[src]",
		),
));
?>
		<?php echo $form->error($model,'description'); ?>
	</div>



	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
