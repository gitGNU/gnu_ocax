<?php
/* @var $this EmailtextController */
/* @var $model Emailtext */

$this->menu=array(
/*
	array('label'=>'List Emailtext', 'url'=>array('index')),
	array('label'=>'Create Emailtext', 'url'=>array('create')),
	
*/
	array('label'=>'View text', 'url'=>array('view', 'id'=>$model->state)),
	array('label'=>'Manage texts', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
