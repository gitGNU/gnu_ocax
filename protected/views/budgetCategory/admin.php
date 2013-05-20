<?php
/* @var $this BudgetCategoryController */
/* @var $model BudgetCategory */


$this->menu=array(
	array('label'=>__('Create category'), 'url'=>array('create')),
);

?>

<h1><?php echo __('Manage budget categories');?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'budget-category-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'code',
		'description',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>
