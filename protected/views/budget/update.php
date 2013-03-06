<?php
/* @var $this BudgetController */
/* @var $model Budget */
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<?php $parent_budget=$model->findByPk($model->parent);?>

<div style="font-size:1.5em; margin:10px; margin-left:0px;">Update budget</div>

<?php echo $this->renderPartial('_form', array('model'=>$model,'parent_budget'=>$parent_budget)); ?>
