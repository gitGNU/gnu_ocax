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

<h1>Text for state "<?php echo Consulta::model()->getHumanStates($model->state); ?>"</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
