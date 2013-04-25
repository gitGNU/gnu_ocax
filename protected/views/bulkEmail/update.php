<?php
/* @var $this BulkEmailController */
/* @var $model BulkEmail */

$this->menu=array(
	array('label'=>__('Create bulk email'), 'url'=>array('create')),
	array('label'=>__('View bulk email'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>__('Manage bulk email'), 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'total_recipients'=>$total_recipients)); ?>
