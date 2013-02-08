<?php
/* @var $this BudgetController */

$year = date('Y');

$criteria = new CDbCriteria;
$criteria->condition = 'year = '.$year.' AND parent is NULL';
$criteria->order = 'weight ASC';
$budgets_raiz = Budget::model()->findAll($criteria);

//get total € of $budgets_raiz
$total_budget=0;
foreach($budgets_raiz as $budget)
	$total_budget=$total_budget+$budget->provision;

?>

<h1>Presupuestos de <?php echo $year;?> Total: <?php echo number_format($total_budget);?>€</h1>

<?php echo $this->renderPartial('_index',array('budgets_raiz'=>$budgets_raiz, 'total_budget'=>$total_budget)); ?>


