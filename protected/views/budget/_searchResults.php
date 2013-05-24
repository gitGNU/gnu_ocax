<?php
/* @var $this BudgetController */
/* @var $data Budget */
?>

<style>
.label { color:grey; font-weight:bold;}
</style>

<?php

$model=Budget::model();

echo '<div style="margin-bottom:25px">';
echo '<span class="highlight_text"><b>';
if($data['code'])
	echo $data['code'].': ';
echo CHtml::encode($data['desc_concept']).'</b></span><br />';
//echo 'Score: '.CHtml::encode($data['score']).'<br />';

$url = Yii::app()->createAbsoluteUrl('budget/view', array('id'=>$data['id']));
echo CHtml::link($url, array('view', 'id'=>$data['id']), array('onclick'=>'js:showBudget('.$data['id'].');return false;')).'<br />';
	
echo '<span class="label">'.CHtml::encode($model->getAttributeLabel('initial_provision')).':</span> ';
echo number_format(CHtml::encode($data['initial_provision']), 2, ',', '.').' €<br />';

echo '<span class="label">'.CHtml::encode($model->getAttributeLabel('actual_provision')).':</span> ';
echo number_format(CHtml::encode($data['actual_provision']), 2, ',', '.').' €<br />';

echo '<p class="highlight_text">'.CHtml::encode($data['text']).'</p>';
echo '</div>';

?>

