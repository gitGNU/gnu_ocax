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

<p>
<div style="margin:-10px">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'csv_id',
		'language',
		'code',
		'concept',
		'common',
	),
)); ?>
</div>
</p>

<?php
	if($model->text){
		echo '<p><span style="font-size:1.2em">'.__('Description').'</span></p>';
		echo $model->description;
	}else
		echo '<span style="font-size:1.2em;color:red;">'.__('Description not defined').'</span>';
?>
