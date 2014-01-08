<?php
/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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

$attributes = array();

$words = __('Details for %s');
$words = str_replace('%s', $model->year, $words);

$showBudgetDetails='<span class="link" style="float:right;font-size:1em" onclick="js:showBudgetDetails()">'.$words.'</span>';
$row =	array(
       		'label'=>__('Anual comparison'),
			'type'=>'raw',
			'value'=>$showBudgetDetails,
		);
$attributes[]=$row;

foreach($budgets as $budget){
	$row =	array(
	       		'label'=>__('Year').' '.$budget->year,
				'type'=>'raw',
				'value'=>format_number($budget->actual_provision).' €',
			);
	$attributes[]=$row;
}

$this->widget('zii.widgets.CDetailView', array(
	'cssFile' => Yii::app()->request->baseUrl.'/css/pdetailview.css',
	'data'=>$model,
	'attributes'=>$attributes,
));



?>
