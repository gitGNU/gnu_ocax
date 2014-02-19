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
	array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'manager')),
	array('label'=>__('Manage enquiries'), 'url'=>array('admin')),
);
$this->inlineHelp=':profiles:team_manager';
?>

<style>           
	#yourOptions { font-size: 1.2em }
	#yourOptions li { margin-bottom: 20px; clear:both}
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

	<div class="title"><?php echo __('Assign enquiry');?></div>
	<p style="font-style:italic"><?php echo __('Please study the enquiry below before deciding on an option').'.'?></p>
	<ol id="yourOptions">
		<li style="margin-bottom:80px">
		<?php
			echo '<div style="float:left">'.__('Assign enquiry').'.</div>';
			echo '<div style="float:left;margin-left:20px">';
			echo '<div class="hint">'.__('Who is this enquiry addressed to?').'</div>';
			echo $form->radioButtonList($model,'addressed_to',
										$model->getHumanAddressedTo(),
										array('labelOptions'=>array('style'=>'display:inline'))
									);
			echo '</div>';
			echo '<div style="float:left;margin-left:20px">';
			echo '<div>'.$form->labelEx($model,'team_member').'</div>';
			$data=CHtml::listData($team_members,'id', 'fullname');
			echo $form->dropDownList($model, 'team_member', $data, array('prompt'=>__('Not assigned')));
			
			echo '</div>';
			echo '<div style="float:left;margin-left:20px">';			
				if(!$model->team_member)
					echo CHtml::submitButton(__('Assign'));
				else
					echo CHtml::submitButton(__('Change team member'));
			echo '</div>';
		?>
		</li>
		<li>
		<?php echo __('Reject the Enquiry').'. '.__('The enquiry is inappropriate').'.';?>
		<?php echo CHtml::button(Config::model()->findByPk('siglas')->value.' '.__('Reject'),array('onclick'=>'js:reject();')); ?>
		</li>
	</ol>

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
		<button onclick="$('.flash-notice').slideUp('fast')">No</button>
    </div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash-success').slideUp('fast');
    	}, 3000);
		});
	</script>
    <div class="flash-success">
		<?php echo Yii::app()->user->getFlash('success');?>
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
