<?php
/* @var $this BudgetDescriptionController */
/* @var $model BudgetDescription */

$this->menu=array(
	array('label'=>__('Create description'), 'url'=>array('create')),
	array('label'=>__('Update description'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>__('Delete description'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>__('Manage descriptions'), 'url'=>array('admin')),
);
?>

<h1><?php echo __('Budget description');?></h1>
<p>

<?php $this->widget('zii.widgets.CDetailView', array(
	'cssFile' => Yii::app()->theme->baseUrl.'/css/pdetailview.css',
	'data'=>$model,
	'attributes'=>array(
		'csv_id',
		'language',
		'code',
		'concept',
		'common',
	),
)); ?>

</p>

<?php
	if($model->text){
		echo '<h2>'.__('Description').'</h2>';
		echo $model->description;
	}else
		echo '<span style="font-size:1.3em;color:red;">'.__('Description not defined').'</span>';
?>
