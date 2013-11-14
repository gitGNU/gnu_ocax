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

$this->menu=array(
	array('label'=>__('View enquiry'), 'url'=>array('teamView', 'id'=>$model->id)),
	array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
	array('label'=>__('List all'), 'url'=>array('managed')),
);
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<script>
function reject(){
	$('#Enquiry_state').val('<?php echo ENQUIRY_REJECTED;?>');
	$('#enquiry-form').submit();
}
function validate(){
	$('#Enquiry_state').val('<?php echo ENQUIRY_ACCEPTED;?>');
	$('#enquiry-form').submit();
}
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->hiddenField($model, 'state');?>

	<div class="title"><?php echo __('Validate enquiry');?></div>

<div class="outer">

	<?php echo $form->errorSummary($model); ?>

<div class="left">
	<div class="row buttons">
		<b><?php echo __('Accept the Enquiry');?></b>
		<div class="hint"><?php echo __('You accept this enquiry as valid');?></div>
		<p style="margin-bottom:37px"></p>
		<?php echo CHtml::button(__('Accept'),array('onclick'=>'js:validate();')); ?>
	</div>

</div>
<div class="right">
	<div class="row buttons">
		<b><?php echo __('Reject the Enquiry');?></b>
		<div class="hint"><?php echo __('The enquiry is inappropriate');?></div>
		<p style="margin-bottom:37px"></p>
		<?php echo CHtml::button(__('Reject'),array('onclick'=>'js:reject();')); ?>
	</div>
</div>
</div>
<div class="clear"></div>

<?php $this->endWidget(); ?>
</div><!-- form -->




<div class="horizontalRule" style="margin-top:20px"></div>
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>

<?php if(Yii::app()->user->hasFlash('prompt_email')):?>
    <div class="flash-notice">
		<?php echo Yii::app()->user->getFlash('prompt_email');?><br />
		<?php 
		$url=Yii::app()->request->baseUrl.'/email/create?enquiry='.$model->id.'&menu=team';
		?>
		<button onclick="js:window.location='<?php echo $url?>';">Sí</button>
		<button onclick="$('.flash-notice').slideUp('fast')">No</button>
    </div>
<?php endif; ?>
