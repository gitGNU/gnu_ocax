<?php
/* @var $this VaultController */
/* @var $model Vault */

$this->breadcrumbs=array(
	'Vaults'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Vault', 'url'=>array('index')),
	array('label'=>'Manage Vault', 'url'=>array('admin')),
);
?>

<h1>Create Vault</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>