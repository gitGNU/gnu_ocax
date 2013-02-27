<?php
/* @var $this EmailtextController */
/* @var $model Emailtext */

$this->breadcrumbs=array(
	'Emailtexts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Emailtext', 'url'=>array('index')),
	array('label'=>'Manage Emailtext', 'url'=>array('admin')),
);
?>

<h1>Create Emailtext</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>