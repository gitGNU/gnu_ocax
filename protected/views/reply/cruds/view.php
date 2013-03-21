<?php
/* @var $this ReplyController */
/* @var $model Reply */

$this->breadcrumbs=array(
	'Replys'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Reply', 'url'=>array('index')),
	array('label'=>'Create Reply', 'url'=>array('create')),
	array('label'=>'Update Reply', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Reply', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Reply', 'url'=>array('admin')),
);
?>

<h1>View Reply #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'enquiry',
		'created',
		'team_member',
		'title',
		'body',
	),
)); ?>
