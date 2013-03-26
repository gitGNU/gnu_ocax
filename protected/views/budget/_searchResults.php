<?php
/* @var $this BudgetController */
/* @var $data Budget */
?>

<style>
.label { color:grey; font-weight:bold;}

</style>

<?php
$yearStr = CHtml::encode($data->year).' - '.CHtml::encode($data->year +1);

echo '<div style="margin-bottom:25px">';
echo '<b>'.CHtml::encode($data->concept).'</b> '.$yearStr.'<br />';
$url = Yii::app()->createAbsoluteUrl('budget/view', array('id'=>$data->id));
echo CHtml::link($url, array('view', 'id'=>$data->id)).'<br />';

if($data->code){
	echo '<span class="label">'.CHtml::encode($data->getAttributeLabel('code')).':</span> ';
	echo CHtml::encode($data->code).'<br />';
}

echo '<span class="label">'.CHtml::encode($data->getAttributeLabel('initial_provision')).':</span> ';
echo number_format(CHtml::encode($data->initial_provision), 2, ',', '.').' €<br />';


echo '<span class="label">'.CHtml::encode($data->getAttributeLabel('actual_provision')).':</span> ';
echo number_format(CHtml::encode($data->actual_provision), 2, ',', '.').' €<br />';

echo '</div>';
?>

