<?php
/* @var $this CmsPageController */
/* @var $model CmsPage */

$this->breadcrumbs=array(
	'Cms Pages'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CmsPage', 'url'=>array('index')),
	array('label'=>'Create CmsPage', 'url'=>array('create')),
	array('label'=>'Update CmsPage', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CmsPage', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CmsPage', 'url'=>array('admin')),
);
?>

<h1>View CmsPage #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'block',
		'weight',
		'published',
	),
)); ?>
