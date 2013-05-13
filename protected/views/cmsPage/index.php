<?php
/* @var $this CmsPageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cms Pages',
);

$this->menu=array(
	array('label'=>'Create CmsPage', 'url'=>array('create')),
	array('label'=>'Manage CmsPage', 'url'=>array('admin')),
);
?>

<h1>Cms Pages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
