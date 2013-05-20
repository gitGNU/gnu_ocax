<?php
/* @var $this BudgetDescriptionController */
/* @var $model BudgetDescription */

$this->breadcrumbs=array(
	'Budget Descriptions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>__('Create description'), 'url'=>array('create')),
	array('label'=>__('Update description'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>__('Delete description'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>__('Manage descriptions'), 'url'=>array('admin')),
);
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'code',
		array(
			'name'=>'category',
			'value'=>BudgetCategory::model()->findByPk($model->category)->code,
		),
		array(
			'name'=>'language',
			'value'=>$model->getHumanLanguages($model->language),
		),
	),
)); ?>

<h1 style="margin-top:20px"><?php echo $model->concept;?></h1>
<?php echo $model->description; ?>
