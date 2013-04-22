
<div class="enquiry">
<div class="title"><?php echo __('The Enquiry')?></div>

<div style="margin:-15px -10px 10px -10px;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>__('Formulated by'),
	        'value'=>$model->user0->fullname.' '.__('on the').' '.$model->created,
		),
		array(
	        'label'=>__('Assigned to'),
	        'value'=>($model->team_member) ? $model->teamMember->fullname.' '.__('on the').' '.$model->assigned : "",
		),
		array(
	        'label'=>__('Type'),
	        'value'=>($model->related_to) ? $model->humanTypeValues[$model->type].' ('.__('reformulated').')' : $model->humanTypeValues[$model->type],
		),
		array(
	        'label'=>__('State'),
	        'value'=>$model->getHumanStates($model->state),
		),
	),
));?>

<?php if($model->state >= 3){	// Enquiry has been submitted to Administration
	$file=File::model()->findByAttributes(array('model'=>'Enquiry','model_id'=>$model->id));
	$link='<a href="'.$file->webPath.'" target="_new">'.$file->name.'</a>';
	$submitted_info=$model->submitted.', '.__('Registry number').':'.$model->registry_number.', Doc: '.$link;

	$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>__('Submitted'),
			'type'=>'raw',
	        'value'=>$submitted_info,
		),
	),
	));
}?>


<?php
if($model->budget){
	$budget=Budget::model()->findByPk($model->budget);
	echo $this->renderPartial('//budget/_enquiryView', array('model'=>$budget));
}
?>


<?php if($reformulatedDataprovider = $model->getReformulatedEnquires()){
$providerData = $reformulatedDataprovider->getData();

echo '<style>.highlight_row{background:#FFDEAD;}</style>';
echo 	'<div style="font-size:1.3em">'.__('The enquiry').' "'.$providerData[0]->title.'" '.__('has been reformulated').
		' '. (count($providerData)-1) .' '.__('time(s)').'</div>';

$this->widget('PGridView', array(
	'id'=>'reforumulated-enquiry-grid',
	'dataProvider'=>$reformulatedDataprovider,
	'template' => '{items}{pager}',
	'rowCssClassExpression'=>'($data->id == '.$model->id.')? "highlight_row":"row_id_".$row." ".($row%2?"even":"odd")',
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/enquiry/teamView',
    ),
	'columns'=>array(
			array(
				'header'=>__('Enquiry'),
				'value'=>'$data[\'title\']',
			),
			array(
				'header'=>__('State'),
				'type' => 'raw',
				'value'=>'$data->getHumanStates($data[\'state\'])',
			),
			array(
				'header'=>__('Formulated'),
				'value'=>'$data[\'created\']',
			),
			array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
}?>

</div>

<div style="background-color:white;	margin: 10px -10px -10px -10px;padding:10px;">
<?php echo '<h1 style="margin-top:10px">'.$model->title.'</h1>';?>
<?php echo $this->renderPartial('//enquiry/_view', array('model'=>$model)); ?>
</div>


</div>
