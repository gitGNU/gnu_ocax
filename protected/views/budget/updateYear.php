<?php

$this->menu=array(
	array('label'=>'Create Año', 'url'=>array('createYear')),
	array('label'=>'List Años', 'url'=>array('adminYears')),
);

?>

<style>
#parent_provision {
	float:left;
	font-size:1.4em;
	margin-left:10px;
}
</style>

<h1>Editar año presupuestario <?php echo ($model->year).' - '.($model->year + 1);?></h1>

<?php echo $this->renderPartial('_formYear', array('model'=>$model)); ?>


