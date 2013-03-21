
<div class="enquiry">
<div class="title"><?php echo __('The Enquiry')?></div>

<div style="margin:-15px -10px 10px -10px;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>__('Submitted by'),
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

<?php
if($model->budget){
	$budget=Budget::model()->findByPk($model->budget);
	echo $this->renderPartial('//budget/_enquiryView', array('model'=>$budget));
}
?>
</div>

<div style="background-color:white;	margin: 10px -10px -10px -10px;padding:10px;">
<?php echo '<h1 style="margin-top:10px">'.$model->title.'</h1>';?>
<?php echo $this->renderPartial('//enquiry/_view', array('model'=>$model)); ?>
</div>


</div>
