<?php
/* @var $this VaultController */
/* @var $model Vault */

$this->breadcrumbs=array(
	'Vaults'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Vault', 'url'=>array('index')),
	array('label'=>'Create Vault', 'url'=>array('create')),
	array('label'=>'View Vault', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Vault', 'url'=>array('admin')),
);
?>

<h1>Update Vault <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>