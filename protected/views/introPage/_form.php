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

/* @var $this IntroPageController */
/* @var $model IntroPage */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'intro-page-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php
		if($listData = getLanguagesArray())
			$show_language=$listData[$content->language];
		else
			$show_language='';
	?>

	<div class="title"><?php echo $title.' ('.$show_language.')';?></div>
	<?php echo $form->errorSummary($model); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php
		if(!$model->isNewRecord  && $listData = getLanguagesArray()){
			echo '<div class="row">';
			echo $form->labelEx($content,'language');
			echo '<div class="hint">'.__('Translations').'</div>';
			echo $form->dropDownList($content, 'language', $listData,
									array('onchange'=>	'location.href="'.Yii::app()->request->baseUrl.
														'/introPage/update/'.$model->id.'?lang="+this.options[this.selectedIndex].value'
									));
			echo '</div>';
		}	
	?>


	<div class="row">
		<?php echo $form->labelEx($model,'weight'); ?>
		<?php echo $form->textField($model,'weight'); ?>
		<?php echo $form->error($model,'weight'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'toppos'); ?>
		<?php echo $form->textField($model,'toppos'); ?>
		<?php echo $form->error($model,'toppos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'leftpos'); ?>
		<?php echo $form->textField($model,'leftpos'); ?>
		<?php echo $form->error($model,'leftpos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'width'); ?>
		<?php echo $form->textField($model,'width'); ?>
		<?php echo $form->error($model,'width'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'published'); ?>
		<?php echo $form->checkBox($model,'published', array('checked'=>$model->published)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($content,'title'); ?>
		<?php echo $form->textField($content,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($content,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($content,'subtitle'); ?>
		<?php echo $form->textField($content,'subtitle',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($content,'subtitle'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($content,'body'); ?>
		<?php echo $form->textArea($content,'body',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($content,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? __('Create') : __('Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
