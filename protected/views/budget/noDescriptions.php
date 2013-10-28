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

/* @var $this BudgetDescriptionController */
/* @var $model BudgetDescription */


$this->menu=array(
	array('label'=>__('Manage years'), 'url'=>array('adminYears')),
);
$this->helpURL='http://ocax.net/pad/p/r.LEojRuTIPvGUscJQ';
?>

<h1><?php echo __('Budgets without description');?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
	'id'=>'budget-grid',
	'dataProvider'=>$dataProvider,
	//'filter'=>$data,
	'columns'=>array(
		array(
			'header'=>__('Internal code'),
			'value'=>'$data[\'csv_id\']',
			'type'=>'raw',
        ),
		array(
			'name'=>__('Code'),
			'value'=>'$data[\'code\']',
			'type'=>'raw',
        ),
        array(
			'name'=>__('Year'),
			'value'=>'$data[\'year\']',
			'type'=>'raw',
        ),
/*
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'view' => array(
					'url'=>'Yii::app()->createUrl("budgetDescription/view/".$data->id)',
				),
			),
		),
*/
	),
)); ?>



