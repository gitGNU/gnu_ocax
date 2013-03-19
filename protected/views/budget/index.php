<?php
/* @var $this BudgetController */
/* @var $model Budget */

//$year = Config::model()->findByPk('year')->value;
$year = $model->year;

$criteria = new CDbCriteria;
$criteria->condition = 'year = '.$year.' AND parent is NULL';
//$criteria->order = 'weight ASC';
$budget_raiz = Budget::model()->find($criteria);

if(!$budget_raiz){
	echo '<h1>'. __('No data available').'</h1>';
}else
	$this->renderPartial('_index',array('budget_raiz'=>$budget_raiz, 'model'=>$model));
?>
