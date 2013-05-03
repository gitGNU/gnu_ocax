<?php
//Yii::app()->clientScript->scriptMap['jquery.js'] = false;
//Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

if(isset($showLinks)){
	$create_enquiry_link = 	'<span style="float:right">'.
							CHtml::link(__('New enquiry'),array('enquiry/create', 'budget'=>$model->id)).
							'</span>';
		
	if($enquiry_count = count($model->enquirys))
		$enquiries = $enquiry_count.' '.CHtml::link(__('enquir(ies) made'), array('budget/view','id'=>$model->id)).' '.$create_enquiry_link;
	else
		$enquiries = __('0 enquiries made').' '.$create_enquiry_link;
		
	$budget_concept= CHtml::link($model->concept, '#', array('onclick'=>'js:showBudgetDescription('.$model->id.');return false;'));
}else{
	if($enquiry_count = count($model->enquirys))
		$enquiries = $enquiry_count.' '.__('enquir(ies) made');
	else
		$enquiries = __('0 enquiries made');
	$budget_concept = $model->concept;
}
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>__('Concept'),
			'type'=>'raw',
			'value'=> $budget_concept,
		),
		array(
	        'label'=>__('Year'),
	        'value'=>$model->getYearString(),
		),
		'code',
		//array('name'=>'initial_provision', 'type'=>'raw', 'value'=>format_number($model->initial_provision).' €'),
		array('name'=>'actual_provision', 'type'=>'raw', 'value'=>format_number($model->actual_provision).' €'),
		array(
	        'label'=>__('Euros per person'),
	        'value'=>format_number($model->actual_provision / $model->getPopulation()).' €',
		),
		array(
	        'label'=>__('Enquiries'),
			'type'=>'raw',
	        'value'=>$enquiries,
		),		
	),
)); ?>

