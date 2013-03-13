<?php
/* @var $this FileController */
/* @var $model File */

$this->menu=array(
	array('label'=>'List File', 'url'=>array('index')),
	array('label'=>'Manage File', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
