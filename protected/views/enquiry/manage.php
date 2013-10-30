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
	array('label'=>__('View enquiry'), 'url'=>array('adminView', 'id'=>$model->id)),
	array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'manager')),
	array('label'=>__('List all'), 'url'=>array('admin')),
);
$this->helpURL='http://ocax.net/pad/p/r.UxhhyJZjoU9Du1Yi';
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<script>
function reject(){
	$('#Enquiry_state').val('rejected');
	$('#enquiry-form').submit();
}
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title"><?php echo __('Manage enquiry');?></div>

<div class="outer">
<div class="left">

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model, 'state');?>

	<div class="row">
		<?php echo $form->labelEx($model,'team_member'); ?>
		<div class="hint"><?php echo __('Team member responsable for this Enquiry');?></div>
		<?php
			$data=CHtml::listData($team_members,'id', 'fullname');
			echo $form->dropDownList($model, 'team_member', $data, array('prompt'=>__('Not assigned')));
		?>
		<?php echo $form->error($model,'team_member'); ?>
	</div>

	<div class="row buttons">
		<?php
			if(!$model->team_member)
				echo CHtml::submitButton(__('Assign'));
			else
				echo CHtml::submitButton(__('Change team member'));
		?>
	</div>

</div>
<div class="right">
	<?php if($model->state < ENQUIRY_AWAITING_REPLY){ // not too late to reject enquiry ?>
	<div class="row buttons">
		<b><?php echo __('Reject the Enquiry');?></b>
		<div class="hint"><?php echo __('The enquiry is inappropriate');?></div>
		<p style="margin-bottom:37px"></p>
		<?php echo CHtml::button(__('Reject'),array('onclick'=>'reject()')); ?>
	</div>
	<?php } ?>
</div>
</div>
<div class="clear"></div>

<?php $this->endWidget(); ?>
</div><!-- form -->



<p></p>
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>

<?php if(Yii::app()->user->hasFlash('prompt_email')):?>
    <div class="flash-notice">
		<?php echo Yii::app()->user->getFlash('prompt_email');?><br />
		<?php 
		$url=Yii::app()->request->baseUrl.'/email/create?enquiry='.$model->id.'&menu=manager';
		?>
			<button onclick="js:window.location='<?php echo $url?>';">Sí</button>
			<button onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?>/enquiry/admin';">No</button>
    </div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('notice')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash-notice').slideUp('fast');
    	}, 3000);
		});
	</script>
    <div class="flash-notice">
		<?php echo Yii::app()->user->getFlash('notice');?>
    </div>
<?php endif; ?>
