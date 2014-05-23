<?php
/* @var $this BackupController */
/* @var $model Backup */

$this->breadcrumbs=array(
	'Backups'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Backup', 'url'=>array('index')),
	array('label'=>'Create Backup', 'url'=>array('create')),
	array('label'=>'Update Backup', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Backup', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Backup', 'url'=>array('admin')),
);
?>

<h1>View Backup #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'vault',
		'filename',
		'initiated',
		'completed',
		'checksum',
		'state',
	),
)); ?>
