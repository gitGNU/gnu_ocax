<?php
/* @var $this BackupController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Backups',
);

$this->menu=array(
	array('label'=>'Create Backup', 'url'=>array('create')),
	array('label'=>'Manage Backup', 'url'=>array('admin')),
);
?>

<h1>Backups</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
