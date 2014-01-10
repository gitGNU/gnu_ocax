<?php
/* @var $this ArchiveController */
/* @var $model Archive */

$this->breadcrumbs=array(
	'Archives'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Archive', 'url'=>array('index')),
	array('label'=>'Create Archive', 'url'=>array('create')),
	array('label'=>'Update Archive', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Archive', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Archive', 'url'=>array('admin')),
);
?>

<h1>View Archive #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'path',
		'mimeType',
		'author',
		'description',
		'created',
	),
)); ?>
