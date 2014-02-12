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
		<?php echo $form->textField($model,'csv_id',array('size'=>20,'maxlength'=>100)); ?>
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
			$languages=explode(',', Config::model()->findByPk('languages')->value);
			$model->language=$languages[0];
			echo $form->hiddenField($model,'language');
		}	
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>20,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

<?php }else{ ?>
	<p>
	<?php $this->widget('zii.widgets.CDetailView', array(
		'cssFile' => Yii::app()->request->baseUrl.'/css/pdetailview.css',
		'data'=>$model,
		'attributes'=>array(
			'csv_id',
			'language',
			'code',
		),
	)); ?>
	</p>
<?php } ?>

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
    'compressorRoute' => 'tinyMce/compressor',
    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',
    'settings' => array(
    	'entity_encoding' => "raw",
	),
    'htmlOptions' => array(
        'rows' => 10,
        'cols' => 80,
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
