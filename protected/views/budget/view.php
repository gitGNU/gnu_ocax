<?php
/* @var $this BudgetController */
/* @var $model Budget */

$this->layout='//layouts/column1';
$root_budget = $model->findByAttributes(array('csv_id'=>$model->csv_id[0], 'year'=>$model->year));
if(!$root_budget){
	$this->render('//site/error',array('code'=>'Budget not found', 'message'=>__('Budget with internal code').' "'.$model->csv_id[0].'" '.__('is not defined')));
	Yii::app()->end();
}
?>

<script>
// this is for interactive graphic
$(function() {
	$('.budget').bind('click', function() {
		budget_id = $(this).attr('budget_id');
		window.location = '<?php echo Yii::app()->request->baseUrl; ?>/budget/view/'+budget_id;
	});
});
</script>


<?php
	echo '<div style="font-size:1.6em">'.$model->getTitle().'</div>';
	if($description = $model->getDescription()){
		echo '<p style="margin-top:15px;">';
		echo $description;
		echo '</p>';

	}
?>

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
		'year',
		array(
	        'name'=>'initial_provision',
			'type' => 'raw',
	        'value'=>format_number($model->initial_provision).' €',
		),
		array(
	        'name'=>'actual_provision',
			'type' => 'raw',
	        'value'=>format_number($model->actual_provision).' €',
		),
	),
)); ?>
</div>
<div class="view" style="float:right;padding:0px;width:48%">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
	        'name'=>'trimester_1',
			'type' => 'raw',
	        'value'=>format_number($model->trimester_1).' €',
		),
		array(
	        'name'=>'trimester_2',
			'type' => 'raw',
	        'value'=>format_number($model->trimester_2).' €',
		),
		array(
	        'name'=>'trimester_3',
			'type' => 'raw',
	        'value'=>format_number($model->trimester_3).' €',
		),
		array(
	        'name'=>'trimester_4',
			'type' => 'raw',
	        'value'=>format_number($model->trimester_4).' €',
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
echo '<div style="font-size:1.3em;margin-top:25px;">'.count($dataProvider->getData()).' '.__('enquiry(s) already made by citizens').':</div>';
$this->widget('PGridView', array(
	'id'=>'enquiry-grid',
	'dataProvider'=>$dataProvider,
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/enquiry/view',
    ),
	'template' => '{items}{pager}',
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

<?php
if(!$model->budgets)
	$parent_budget=$model->parent0;
else
	$parent_budget=$model;
$graph_width=897;
?>

<style>
.graph {
	background-image: url('<?php echo Yii::app()->theme->baseUrl; ?>/images/graph_paper.png');
	background-repeat:repeat;
}
.actual_provision_bar{
	background-color:#BFBFBF;
}
.initial_provision_bar{
	margin-bottom:20px;
	background-color:#DBDBDB;
}
.key{
	padding:2px;
	padding-left:10px;
	padding-right:60px;
}
</style>

<div class="view" style="padding:0px;width:<?php echo $graph_width;?>">
	<div style="background:#CAE1FF;font-size:1.3em;padding:5px;">
	<?php
	$percent = percentage($parent_budget->actual_provision,$root_budget->actual_provision);
	echo '\''.$parent_budget->concept.'\' '.__('constitutes ').' '.$percent.'% '.__('of the total anual budget').' ';
	echo __('and is comprised of the following budgets').'.';
	?>
	</div>
	<div style="background:#F0F8FF;padding:10px;margin-bottom:10px;">
	<?php echo __('Key');?>:
	<span class="key" style="margin-left:15px;background:#BFBFBF"><?php echo __('Actual provision');?></span>
	<?php /* <span class="key" style="margin-left:25px;background:#DBDBDB"><?php echo __('Initial provision');?></span> */?>
	<?php if($parent_budget->parent && $parent_budget->parent0->parent && $parent_budget->parent0->parent0->parent){
		echo '<span style="float:right">'.CHtml::link(__('Up one level'),array('budget/view', 'id'=>$parent_budget->parent0->id)).'</span>';
	}?>
	</div>

	<div class="graph">
	<?php $this->renderPartial('_interactive',array('model'=>$model,
													'root_budget'=>$root_budget,
													'parent_budget'=>$parent_budget,
													'graph_width'=>$graph_width));?>
	</div>
</div>

