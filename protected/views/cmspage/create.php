<?php
/* @var $this CmspageController */
/* @var $model CmsPage */

$this->menu=array(
	array('label'=>'Manage CmsPage', 'url'=>array('admin')),
);
?>

<h1>Create CmsPage</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
