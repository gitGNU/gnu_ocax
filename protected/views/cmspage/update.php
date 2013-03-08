<?php
/* @var $this CmspageController */
/* @var $model CmsPage */

$this->menu=array(
	array('label'=>'Create CmsPage', 'url'=>array('create')),
	array('label'=>'View CmsPage', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CmsPage', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'title'=>'Update \''.$model->pagename.'\'')); ?>
