<?php
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
	array('label'=>'View User', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<h1>Change Roles '<?php echo $model->username; ?>'</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'fullname',
		'email',
		'is_socio',
		'joined',
	),
)); ?>

<h2>Roles</h2>

<div class="outer" style="margin-top:10px">
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php changeColumn();?>
	<div class="row">
		<?php echo $form->labelEx($model,'is_team_member'); ?>
		<?php echo $form->checkBox($model,'is_team_member', array('checked'=>$model->is_team_member)); ?>
		Responde a las consultas.
	</div>
	</div>

	<?php changeColumn();?>
	<div class="row">
		<?php echo $form->labelEx($model,'is_editor'); ?>
		<?php echo $form->checkBox($model,'is_editor', array('checked'=>$model->is_editor)); ?>
		CMS site editor
	</div>
	</div>

	<?php changeColumn();?>
	<div class="row">
		<?php echo $form->labelEx($model,'is_manager'); ?> 
		<?php echo $form->checkBox($model,'is_manager', array('checked'=>$model->is_manager)); ?>
		Encarga consultas a otros compañeros
	</div>
	</div>

	<?php changeColumn();?>
	<div class="row">
		<?php echo $form->labelEx($model,'is_admin'); ?>
		<?php echo $form->checkBox($model,'is_admin', array('checked'=>$model->is_admin)); ?>
		Administrar this site 
	</div>
	</div>

	<div class="clear"></div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>

