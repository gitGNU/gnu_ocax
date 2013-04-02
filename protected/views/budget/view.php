<?php
/* @var $this BudgetController */
/* @var $model Budget */
$this->layout='//layouts/column1';


?>

<style>
.graph {
background-image: url('<?php echo Yii::app()->theme->baseUrl; ?>/images/graph_paper.png');
background-repeat:repeat;
}
</style>

<script>
// this is for interactive graphic
$(function() {
	$('.budget').bind('click', function() {
		budget_id = $(this).attr('budget_id');
		window.location = '<?php echo Yii::app()->request->baseUrl; ?>/budget/view/'+budget_id;
	});
});
</script>


<?php echo '<h1>'.CHtml::encode($model->concept).' '.$model->getYearString().'</h1>';?>

<?php
$dataProvider=new CActiveDataProvider('Enquiry', array(
    'criteria'=>array(
        'condition'=>'budget = '.$model->id,
        'order'=>'created DESC',
    ),
    'pagination'=>array(
        'pageSize'=>20,
    ),
));
?>

<div class="view" style="float:left;padding:0px;width:48%">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'code',
		//'concept',
		array(
	        'name'=>'initial_provision',
			'type' => 'raw',
	        'value'=>number_format(CHtml::encode($model->initial_provision), 2, ',', '.').' €',
		),
		array(
	        'name'=>'actual_provision',
			'type' => 'raw',
	        'value'=>number_format(CHtml::encode($model->actual_provision), 2, ',', '.').' €',
		),
	),
)); ?>
</div>
<div class="view" style="float:right;padding:0px;width:48%">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
	        'name'=>'spent_t1',
			'type' => 'raw',
	        'value'=>number_format(CHtml::encode($model->spent_t1), 2, ',', '.').' €',
		),
		array(
	        'name'=>'spent_t2',
			'type' => 'raw',
	        'value'=>number_format(CHtml::encode($model->spent_t2), 2, ',', '.').' €',
		),
		array(
	        'name'=>'spent_t3',
			'type' => 'raw',
	        'value'=>number_format(CHtml::encode($model->spent_t3), 2, ',', '.').' €',
		),
		array(
	        'name'=>'spent_t4',
			'type' => 'raw',
	        'value'=>number_format(CHtml::encode($model->spent_t4), 2, ',', '.').' €',
		),
	),
)); ?>
</div>
<div style="clear:both"></div>

<?php
if($dataProvider->getData()){
	echo '<p style="font-size:1.3em">'.__('Do you wish to').' '.CHtml::link(__('make an enquiry'),array('enquiry/create', 'budget'=>$model->id));
	echo ' '.__('about this budget').'?</p>';
}else{
	echo '<p style="font-size:1.3em">'.__('No enquiries have been made about this budget yet').'. '.__('Do you wish to').' ';
	echo CHtml::link(__('make an enquiry'),array('enquiry/create', 'budget'=>$model->id)).'?</p>';
}
?>

<p>
<?php
if($dataProvider->getData()){
echo '<div style="font-size:1.3em;margin-top:25px;">'.__('Enquirys already made by citizens').':</div>';
$this->widget('PGridView', array(
	'id'=>'enquiry-grid',
	'dataProvider'=>$dataProvider,
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/enquiry/view',
    ),
	'ajaxUpdate'=>true,
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
			array(
				'header'=>'Enquiry',
				'name'=>'title',
				'value'=>'$data[\'title\']',
			),
			array(
				'header'=>'Estat',
				'name'=>'state',
				'type' => 'raw',
				'value'=>'$data->getHumanStates($data[\'state\'])',
			),
            array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
}
?>
</p>

<div class="graph">
<?php $this->renderPartial('_interactive',array('model'=>$model));?>
</div>




