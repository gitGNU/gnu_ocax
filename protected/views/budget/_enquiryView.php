
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>__('Concept'),
			'type'=>'raw',
			'value'=> CHtml::link($model->concept, array('budget/view','id'=>$model->id)),
		),
		'code',
		array('name'=>'provision', 'type'=>'raw', 'value'=>number_format($model->provision).'€'),
		array('name'=>'spent', 'type'=>'raw', 'value'=>number_format($model->spent).'€'),
	),
)); ?>

