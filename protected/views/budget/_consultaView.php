
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'concept',
		'code',
		array('name'=>'provision', 'type'=>'raw', 'value'=>number_format($model->provision).'€'),
		array('name'=>'spent', 'type'=>'raw', 'value'=>number_format($model->spent).'€'),
	),
)); ?>

