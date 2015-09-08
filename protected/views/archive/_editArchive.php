<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2015 OCAX Contributors. See AUTHORS.

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

/* @var $this ArchiveController */
/* @var $model Archive */
/* @var $form CActiveForm */

Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

?>
<style>
.divider {
margin: 0px -10px 5px -10px;
border-bottom: 1px solid #CDCBC9;
}
</style>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'archive-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->baseUrl.'/archive/update/'.$model->id,
)); ?>

	<?php
			if ($model->is_container){
				$word = __('Folder');
			}else{
				$word = __('File');
			}
	?>
	
	<div class="modalTitle"><?php echo $word;?></div>

	<?php
		echo $form->label($model, 'name');
		echo '<div class="errorMessage" id="name_error"></div>';
		echo $form->textField($model,'name',array('style'=>'width:485px'));
	?>

	<?php
		echo $form->label($model, 'description');
		echo '<div class="hint">'.__('A description of this folder').'</div>';
		echo '<div class="errorMessage" id="description_error"></div>';
		echo $form->textArea($model,'description',array('rows'=>4, 'style'=>'width:485px'));
	?>

	<div class="row buttons">
		<input type="button" value="<?php echo __('Save changes')?>" onClick="js:validate();" />
		<input type="button" value="<?php echo __('Cancel')?>" onClick="js:$('#files_popup').bPopup().close();" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<div class="divider"></div>

<?php
	echo '<div style="font-size:16px; margin-bottom: 10px;">';
	if (!$model->is_container || !$model->findByAttributes(array('container'=>$model->id))){
		if ($model->is_container){
			$word = __('Delete this folder');
		}else{
			$word = __('Delete this file');
		}
			
		echo $word.'<i class="icon-cancel-circled red" style="cursor:pointer" onClick="js:deleteArchive('.$model->id.')"></i>';
			
	}else if ($model->is_container){
		echo __('Cannot delete').'. '.__('This folder is not empty').'.';
	}
	echo '</div>';
?>

<?php
	echo '<div style="font-size:16px;">';
	if ($model->is_container){
		$word = __('Move this folder to ..');
	}else{
		$word = __('Move this file to ..');
	}
			
	echo $word;
			
	echo '</div>';
?>

