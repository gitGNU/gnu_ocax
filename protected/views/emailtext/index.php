<?php
/* @var $this EmailtextController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Emailtexts',
);

$this->menu=array(
	array('label'=>'Create Emailtext', 'url'=>array('create')),
	array('label'=>'Manage Emailtext', 'url'=>array('admin')),
);
?>

<h1>Emailtexts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
