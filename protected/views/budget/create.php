<?php
/* @var $this BudgetController */
/* @var $model Budget */
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'title'=>'Create sub budget')); ?>
