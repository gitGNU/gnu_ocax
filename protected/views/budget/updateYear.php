<?php

$totalBudgets = count(Budget::model()->findAllBySql('SELECT id FROM budget WHERE year = '.$model->year.' AND parent IS NOT NULL'));

$this->menu=array(
	array('label'=>'Importar partidas', 'url'=>array('csv/importCSV/'.$model->year)),
	array('label'=>'Listar Años', 'url'=>array('adminYears')),
);

if($totalBudgets){
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


