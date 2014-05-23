<?php
/* @var $this VaultController */
/* @var $model Vault */

$this->breadcrumbs=array(
	'Vaults'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Vault', 'url'=>array('index')),
	array('label'=>'Create Vault', 'url'=>array('create')),
	array('label'=>'Update Vault', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Vault', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Vault', 'url'=>array('admin')),
);
?>

<h1>View Vault #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'host',
		'type',
		'schedule',
		'created',
		'state',
	),
)); ?>
