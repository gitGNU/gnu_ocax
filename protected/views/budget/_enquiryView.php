
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>__('Concept'),
			'type'=>'raw',
			'value'=> isset($showLinks)? CHtml::link($model->concept, array('budget/view','id'=>$model->id)): $model->concept,
		),
		'code',
		array('name'=>'initial_provision', 'type'=>'raw', 'value'=>number_format($model->initial_provision).'€'),
		array('name'=>'actual_provision', 'type'=>'raw', 'value'=>number_format($model->actual_provision).'€'),
	),
)); ?>

