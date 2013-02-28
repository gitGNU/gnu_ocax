<?php
/* @var $this ConfigController */
/* @var $model Config */
?>

<h1>Global parameters</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'config-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'parameter',
		'value',
		'description',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}',
		),
	),
)); ?>
