<?php
/* @var $this BudgetController */
/* @var $model Budget */

//$year = Config::model()->findByPk('year')->value;
$year = $model->year;

$criteria = new CDbCriteria;
$criteria->condition = 'year = '.$year.' AND parent is NULL';
//$criteria->order = 'weight ASC';
$root_budget = Budget::model()->find($criteria);

if(!$root_budget){
	echo '<h1>'. __('No data available').'</h1>';
}else
	$this->renderPartial('_index',array('budget_raiz'=>$root_budget, 'model'=>$model));
?>
