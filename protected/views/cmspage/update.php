<?php
/* @var $this CmspageController */
/* @var $model CmsPage */

$this->menu=array(
	array('label'=>'Create CmsPage', 'url'=>array('create')),
	array('label'=>'View CmsPage', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CmsPage', 'url'=>array('admin')),
);
?>

<h1>Update "<?php echo $model->pagename; ?>"</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
