<?php
/* @var $this BudgetController */
/* @var $model Budget */

$this->menu=array(
	array('label'=>'Create Año', 'url'=>array('createYear')),
	array('label'=>'Listar todas partidas', 'url'=>array('admin')),
);

?>

<h1>Gestionar años presupuestarios</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'budget-grid',
	'dataProvider'=>$years,
	//'filter'=>$model,
	'columns'=>array(
		'year',
		//'code',
		//'concept',
		'provision',
		'spent',

		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
			'buttons'=>array(
				'update'=>array('url'=>'Yii::app()->createUrl("budget/updateYear", array("id"=>$data->id))',),
			),
		),

	),
)); ?>
