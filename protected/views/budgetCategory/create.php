<?php
/* @var $this BudgetCategoryController */
/* @var $model BudgetCategory */


$this->menu=array(
	array('label'=>__('Manage categories'), 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'title'=>__('Create category'))); ?>
