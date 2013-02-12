<?php
/* @var $this ConfigController */
/* @var $model Config */

$this->menu=array(
	array('label'=>'Create parámetro', 'url'=>array('create')),
	array('label'=>'Listar parámetros', 'url'=>array('admin')),
);
?>

<h1>Update Configuración: <?php echo $model->parameter; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
