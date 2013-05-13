<?php
/* @var $this CmsPageController */
/* @var $model CmsPage */

$this->menu=array(
	array('label'=>__('Manage CmsPage'), 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'content'=>$content,'title'=>__('Create CMS Page'))); ?>
