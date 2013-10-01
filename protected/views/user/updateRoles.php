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
 
/* @var $this UserController */
/* @var $model User */


$column=0;
function changeColumn()
{
	global $column;
	if($column==0)
	{
		echo '<div class="clear"></div>';
		echo '<div class="left">';
		$column=1;
	}
	else
	{
		echo '<div class="right">';
		$column=0;
	}
}

$this->menu=array(
	array('label'=>__('View User'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>__('Manage Users'), 'url'=>array('admin')),
);
?>

<style>           
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title"><?php echo __('Change roles')?></div>

	<?php $this->widget('zii.widgets.CDetailView', array(
		'cssFile' => Yii::app()->request->baseUrl.'/css/pdetailview.css',
		'data'=>$model,
		'attributes'=>array(
			'username',
			'fullname',
			'email',
			'joined',
			'is_socio',
		),
	)); ?>

<div>
	<?php changeColumn();?>
	<div class="row">
		<?php echo $form->labelEx($model,'is_team_member'); ?>
		<?php echo $form->checkBox($model,'is_team_member', array('checked'=>$model->is_team_member)); ?>
		Responde a las enquirys encargadas.
	</div>
	</div>

	<?php changeColumn();?>
	<div class="row">
		<?php echo $form->labelEx($model,'is_editor'); ?>
		<?php echo $form->checkBox($model,'is_editor', array('checked'=>$model->is_editor)); ?>
		CMS site editor.
	</div>
	</div>

	<?php changeColumn();?>
	<div class="row">
		<?php echo $form->labelEx($model,'is_manager'); ?> 
		<?php echo $form->checkBox($model,'is_manager', array('checked'=>$model->is_manager)); ?>
		Encarga enquirys a otros compañeros.
	</div>
	</div>

	<?php changeColumn();?>
	<div class="row">
		<?php echo $form->labelEx($model,'is_admin'); ?>
		<?php echo $form->checkBox($model,'is_admin', array('checked'=>$model->is_admin)); ?>
		Administer Site, Users, Budgets.
	</div>
	</div>

	<div class="clear"></div>
	<div class="row buttons">
		<?php echo CHtml::submitButton(__('Save')); ?>
	</div>
</div>

<?php $this->endWidget(); ?>


</div><!-- form -->



