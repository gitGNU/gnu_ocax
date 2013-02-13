<?php
/* @var $this BudgetController */
/* @var $model Budget */
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<?php
if($parent_id){
	$parent_budget=$model->findByPk($parent_id);
	$model->parent = $parent_budget->id;
	$model->year = $parent_budget->year;
}
?>


<div style="font-size:1.5em; margin:10px; margin-left:0px;">Crear partida del <?php echo $model->year?> - <?php echo $model->year+1?></div>

<?php echo $this->renderPartial('_form', array('model'=>$model,'parent_budget'=>$parent_budget)); ?>
