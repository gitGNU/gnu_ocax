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

/* @var $this CommentController */
/* @var $model Comment */
/* @var $form CActiveForm */
?>


<div>
<b><?php echo $fullname;?></b> <?php echo __('comments')?> ..</br />
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'action'=>'',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>false,
)); ?>
	<?php echo $form->hiddenField($model,'enquiry');?>
	<?php echo $form->hiddenField($model,'reply');?>

	<div class="row">
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>80)); ?>
	</div>

	<div class="row" style="margin-top:10px">
		<input type="button" onClick="js:submitComment($(this).parents('form:first'));" value="<?php echo __('Publish');?>" />
		<input type="button" onClick="js:cancelComment();" value="<?php echo __('Cancel');?>" />
		<img style="vertical-align:middle;display:none" class="loading_gif" src="<?php echo Yii::app()->theme->baseUrl;?>/images/loading.gif" />
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->

