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

/* @var $this VaultController */
/* @var $model Vault */
/* @var $form CActiveForm */
?>

<?php
	// get the available days for backups specified by remote admin	
	$opts = array('http' => array(
							'method'  => 'GET',
							'header'  => 'Content-type: application/x-www-form-urlencoded',
							'ignore_errors' => '1',
							'timeout' => 15,
							'user_agent' => 'ocax-'.getOCAXVersion(),
						));			
	$vaultName = rtrim($model->host2VaultName(Yii::app()->getBaseUrl(true)), '-remote');				
	$context = stream_context_create($opts);
	
	$reply=Null;
	$reply = @file_get_contents($model->host.'/vault/getSchedule?key='.$model->key.'&vault='.$vaultName, false, $context);
	if($reply !== Null && strlen($reply) == 7){
		$model->schedule = $reply;
	}else
		$model->schedule = 'error!'
?>

<div class="form" style="margin-top:-20px;">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vault-form',
	'action' => Yii::app()->createUrl('vault/update/'.$model->id),
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'schedule'); ?>
		<?php echo $form->textField($model,'schedule',array('size'=>20,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'schedule'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(__('Programme')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
