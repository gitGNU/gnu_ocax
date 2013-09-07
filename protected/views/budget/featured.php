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
/* @var $model Budget */

$criteria = new CDbCriteria;
$criteria->condition = 'parent IS NULL AND year ='.$model->year;
$this_year=$model->find($criteria);

$this->menu=array(
	array('label'=>'Edit year '.$model->year, 'url'=>array('/budget/updateYear/'.$this_year->id)),
	array('label'=>'List Years', 'url'=>array('adminYears')),
);
?>

<script>
function featureBudget(budget_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/feature',
		type: 'GET',
		async: false,
		data: {'id': budget_id },
		success: function(data){
			if(data != 0){
  				$.fn.yiiGridView.update('budget-grid', {
					data: $(this).serialize()
				});
			}
		},
		error: function() {
			alert("Error on Feature budget");
		}
	});
}
</script>


<h1><?php echo __('Featured budgets').' '.($model->year).' - '.($model->year + 1)?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->theme->baseUrl.'/css/pgridview.css',
	'id'=>'budget-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'featured',
		'concept',
		'code',
		'csv_id',
		array(
			'class'=>'CButtonColumn',
			'buttons' => array(
				'feature' => array(
					'label'=> __('Feature budget'),
					'url'=> '"javascript:featureBudget(\"".$data->id."\");"',
					'imageUrl' => Yii::app()->theme->baseUrl.'/images/insert_icon.png',
					'visible' => 'true',
				)

			),
			'template'=>'{feature}',
		),
	),
)); ?>

