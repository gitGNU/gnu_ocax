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


$this->menu=array(
	array('label'=>'Manage Backups', 'url'=>array('backup/admin')),
);
?>

<style>
	/* .step { display:none } */
	h2 { margin-top:10px; margin-bottom:5px; }
	.step p { margin-bottom: 0px }
</style>

<script>


</script>

<h1><?php echo __('Create a vault');?></h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vault-form',
	'enableAjaxValidation'=>false,
)); ?>

<div id="question_1" class="row step" style="display:block">
<h2><?php echo __('What type of vault are you creating?');?></h2>
<p>
<?php
	$vaultType = array(
				0=>__('I want to allow someone to save their copies on my server'),
				1=>__('I want to save my copies on another server'),
	);
	echo $form->radioButtonList($model,'type',
								$vaultType,
								array(	'labelOptions'=>array('style'=>'display:inline'),
										'separator'=>'<br />',
										'onchange'=>'js:showChangedAddressedToMSG(this);return false;',
									)
							);
	echo $form->error($model,'type');
?>
</p>
</div>

<div id="question_2" class="row step">
<h2>Every vault has two observatories</h2>
<p>
1.	<?php echo Yii::app()->getBaseUrl(true);?><br />
2.	<?php echo $form->textField($model,'host',array('size'=>30,'maxlength'=>255)); ?>
	<?php echo $form->error($model,'host'); ?>
</p>
</div>

<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>

<?php $this->endWidget(); ?>
</div>

<?php
//echo $this->renderPartial('_form', array('model'=>$model));
?>
