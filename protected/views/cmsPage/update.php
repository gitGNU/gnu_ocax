<?php
/* @var $this CmsPageController */
/* @var $model CmsPage */

$this->menu=array(
	array('label'=>__('Create CmsPage'), 'url'=>array('create')),
	array('label'=>__('View CmsPage'), 'url'=>array('view', 'id'=>$model->id,'lang'=>$content->language)),
	array('label'=>__('Manage CmsPage'), 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'content'=>$content,'title'=>__('Update CMS Page'))); ?>
