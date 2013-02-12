<?php
/* @var $this ConfigController */
/* @var $model Config */

$this->breadcrumbs=array(
	'Configs'=>array('index'),
	$model->parameter,
);

$this->menu=array(
	array('label'=>'List Config', 'url'=>array('index')),
	array('label'=>'Create Config', 'url'=>array('create')),
	array('label'=>'Update Config', 'url'=>array('update', 'id'=>$model->parameter)),
	array('label'=>'Delete Config', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->parameter),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Config', 'url'=>array('admin')),
);
?>

<h1>View Config #<?php echo $model->parameter; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'parameter',
		'value',
		'description',
	),
)); ?>
