<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

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
echo CHtml::link($url, array('view', 'id'=>$data['id']), array('onclick'=>'js:showBudget('.$data['id'].', this);return false;')).'<br />';
	
echo '<span class="label">'.CHtml::encode($model->getAttributeLabel('initial_provision')).':</span> ';
echo number_format(CHtml::encode($data['initial_provision']), 2, ',', '.').' €<br />';

echo '<span class="label">'.CHtml::encode($model->getAttributeLabel('actual_provision')).':</span> ';
echo number_format(CHtml::encode($data['actual_provision']), 2, ',', '.').' €<br />';

echo '<p class="highlight_text">'.CHtml::encode($data['text']).'</p>';
echo '</div>';

?>

