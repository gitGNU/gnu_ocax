<?php
/* @var $this ConfigController */
/* @var $model Config */

$this->menu=array(
	array('label'=>'Listar parámetros', 'url'=>array('admin')),
);
?>

<h1>Create Config</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
