<?php

$totalBudgets = count(Budget::model()->findAllBySql('SELECT id FROM budget WHERE year = '.$model->year.' AND parent IS NOT NULL'));

$this->menu=array(
	array('label'=>'Importar partidas', 'url'=>array('csv/importCSV/'.$model->year)),
	array('label'=>'Listar Años', 'url'=>array('adminYears')),
);

if($totalBudgets){
	$downloadCsv = array( array('label'=>'Download CSV', 'url'=>(Yii::app()->request->baseUrl.'/files/csv/'.$model->year.'-internal.csv')));
	array_splice( $this->menu, 1, 0, $downloadCsv );
	$deleteDatos = array( array( 'label'=>'Borrar partidas', 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:deleteBudgets();') ) );
	array_splice( $this->menu, 1, 0, $deleteDatos );
}
?>

<script>
function deleteBudgets(){
	ans = confirm('Seguro que quieres borrar <?php echo $totalBudgets;?> partidas?');
	if (ans)
		window.location = '<?php echo Yii::app()->request->baseUrl; ?>/budget/deleteYearsBudgets/<?php echo $model->id;?>';
}
</script>

<h1>Editar año presupuestario <?php echo ($model->year).' - '.($model->year + 1);?></h1>

<?php echo $this->renderPartial('_formYear', array('model'=>$model, 'totalBudgets'=>$totalBudgets)); ?>

<?php
if($consultas->getData()){
echo '<div style="font-size:1.5em">Consultas presupuestarias del '.($model->year).' - '.($model->year + 1).'</div>';
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'consultas-grid',
	'dataProvider'=>$consultas,
	'columns'=>array(
		array(
			'name'=>'consulta title',
			'value'=>'$data->title',
		),
		'state',
		array(
			'name'=>'csv budget code',
			'value'=>'$data->budget0->csv_id',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {delete}',
			'buttons'=>array(
				'view' => array(
					'label'=>'View',
		            'url'=>'Yii::app()->createUrl("consulta/view", array("id"=>$data->id))',
				),
				'delete' => array(
					'label'=>'Delete consulta',
		            'url'=>'Yii::app()->createUrl("consulta/delete", array("id"=>$data->id))',
				),
			),
		),
	),
));

}
?>


