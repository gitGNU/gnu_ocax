<?php
/* @var $this VaultController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Vaults',
);

$this->menu=array(
	array('label'=>'Create Vault', 'url'=>array('create')),
	array('label'=>'Manage Vault', 'url'=>array('admin')),
);
?>

<h1>Vaults</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
