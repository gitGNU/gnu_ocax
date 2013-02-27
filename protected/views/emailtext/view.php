<?php
/* @var $this EmailtextController */
/* @var $model Emailtext */

$this->breadcrumbs=array(
	'Emailtexts'=>array('index'),
	$model->state,
);

$this->menu=array(
	array('label'=>'List Emailtext', 'url'=>array('index')),
	array('label'=>'Create Emailtext', 'url'=>array('create')),
	array('label'=>'Update Emailtext', 'url'=>array('update', 'id'=>$model->state)),
	array('label'=>'Delete Emailtext', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->state),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Emailtext', 'url'=>array('admin')),
);
?>

<h1>View Emailtext #<?php echo $model->state; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'state',
		'body',
	),
)); ?>
