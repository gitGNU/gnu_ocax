<?php
/* @var $this BudgetController */
/* @var $model Budget */
?>

<?php $parent_budget=$model->findByPk($model->parent);?>

<div style="font-size:1.5em; margin:10px; margin-left:0px;">Actualizar partida del <?php echo $model->year?> - <?php echo $model->year+1?></div>

<?php echo $this->renderPartial('_form', array('model'=>$model,'parent_budget'=>$parent_budget)); ?>
