<?php
//Yii::app()->clientScript->scriptMap['jquery.js'] = false;
//Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

if(Yii::app()->user->isTeamMember() || Yii::app()->user->isManager())
	$enquiry_count = count($model->enquirys);
else{
	if($user_id=Yii::app()->user->getUserID()){
		$criteria = new CDbCriteria;
		$criteria->condition = 'budget = '.$model->id.' AND user = "'.$user_id.'"';
		$enquiry_count = count(Enquiry::model()->findAll($criteria));		
	
		$criteria = new CDbCriteria;
		$criteria->condition = 'budget = '.$model->id.' AND state >= '.ENQUIRY_ACCEPTED.' AND NOT user = "'.$user_id.'"';
		$enquiry_count = $enquiry_count + count(Enquiry::model()->findAll($criteria));
	}else{
		$criteria = new CDbCriteria;
		$criteria->condition = 'budget = '.$model->id.' AND state >= '.ENQUIRY_ACCEPTED;
		$enquiry_count = count(Enquiry::model()->findAll($criteria));
	}
}

if(isset($showLinks)){
	$create_enquiry_link = 	'<span style="float:right">'.
							CHtml::link(__('New enquiry'),array('enquiry/create', 'budget'=>$model->id)).
							'</span>';
	if($enquiry_count){
		if($enquiry_count == 1){
			if(isset($enquiry) && $enquiry->budget == $model->id)
				$enquiries = __('1 enquiry made').' '.$create_enquiry_link;
			else
				$enquiries = CHtml::link(__('1 enquiry made'), array('budget/view','id'=>$model->id)).' '.$create_enquiry_link;
		}else
			$enquiries = CHtml::link($enquiry_count.' '.__('enquiries made'), array('budget/view','id'=>$model->id)).' '.$create_enquiry_link;

	}else
		$enquiries = '0 '.__('enquiries made').' '.$create_enquiry_link;
		
	$budget_concept= CHtml::link($model->getConcept(), array('view', 'id'=>$model->id), array('onclick'=>'js:showBudget('.$model->id.');return false;'));
	
}else{
	if($enquiry_count){
		if($enquiry_count == 1)
			$enquiries = __('1 enquiry made');
		else
			$enquiries = $enquiry_count.' '.__('enquiries made');
	}else
		$enquiries = '0 '.__('enquiries made');
	$budget_concept = $model->getConcept();
}
?>

<?php
$attributes=array(
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
	);

if(!isset($noConcept)){
	$concept =	array(
					array(
						'name'=>__('Concept'),
						'type'=>'raw',
						'value'=> $budget_concept,
					),	
				);
	array_splice( $attributes, 0, 0, $concept );
}

?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>$attributes,
)); ?>

