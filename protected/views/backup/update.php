<?php
/* @var $this BackupController */
/* @var $model Backup */

$this->breadcrumbs=array(
	'Backups'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Backup', 'url'=>array('index')),
	array('label'=>'Create Backup', 'url'=>array('create')),
	array('label'=>'View Backup', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Backup', 'url'=>array('admin')),
);
?>

<h1>Update Backup <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>