<?php
/* @var $this BackupController */
/* @var $model Backup */

$this->breadcrumbs=array(
	'Backups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Backup', 'url'=>array('index')),
	array('label'=>'Manage Backup', 'url'=>array('admin')),
);
?>

<h1>Create Backup</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>