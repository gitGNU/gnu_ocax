<?php
/* @var $this BudgetDescriptionController */
/* @var $model BudgetDescription */


$this->menu=array(
	array('label'=>__('Create description'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#budget-description-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo __('Manage Budget Descriptions');?></h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'budget-description-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'code',
		array(
			'name'=>'type',
			'value'=>'$data->getHumanTypes($data->type)',
			'filter'=>CHtml::activeDropDownList($model, 'type', $model->getHumanTypes(), array('prompt'=>__('Not filtered'))),
		),
		'language',
		'concept',
		//'description',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
