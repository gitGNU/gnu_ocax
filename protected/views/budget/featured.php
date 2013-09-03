<?php
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

