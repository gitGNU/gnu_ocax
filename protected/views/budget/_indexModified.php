<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2015 OCAX Contributors. See AUTHORS.

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
/* @var $model Budget */

echo '<div class="horizontalRule"></div>';
echo '<div style="font-size: 1.5em; margin-top: -10px;">'.__('Modifications').'</div>';
echo '<div style="margin-top:10px;">';

$this->widget('PGridView', array(
	'id'=>'budget-grid',
	'template' => '{items}{pager}',
	'dataProvider'=>$model->modifiedSearch(),
    'onClick'=>array(
        'type'=>'javascript',
        'call'=>'showBudget',
    ),
	'ajaxUpdate'=>true,
	'columns'=>array(
			array(
				'header'=>__('Category'),
				'value'=>'$data->getCategory()',
			),	
			'code',
			array(
				'header'=>__('Budget'),
				'name'=>'concept',
				'value'=>'$data->concept',
			),
			array(
				'name'=>__('initial_provision'),
				'type'=>'raw',
				'value'=>function($data){
					return format_number($data->initial_provision);
				},
				'headerHtmlOptions'=>array('style'=>'text-align: right'),
				'htmlOptions'=>array('style'=>'text-align: right'),
			),	
			array(
				'name'=>__('actual_provision'),
				'type'=>'raw',
				'value'=>function($data){
					return format_number($data->actual_provision);
				},
				'headerHtmlOptions'=>array('style'=>'text-align: right'),
				'htmlOptions'=>array('style'=>'text-align: right'),
			),			
			array(
				'header'=>__('Difference'),
				'type'=>'raw',
				'value'=>function($data){
					$diff = $data->actual_provision - $data->initial_provision;
					//$diff = $data->getProvisionModification();
					if ($diff > 0){
						return '<span class="green">'.format_number($diff).'</span>';
					}
					return '<span class="red">'.format_number($diff).'</span>';
				},
				'headerHtmlOptions'=>array('style'=>'text-align: right'),
				'htmlOptions'=>array('style'=>'text-align: right'),
			),
			array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
echo '</div>';
?>
