
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>'Submitted por',
	        'value'=>$model->user0->fullname.' el día '.$model->created,
		),
		array(
	        'label'=>'Asignada a',
	        'value'=>($model->team_member) ? $model->teamMember->fullname.' on the '.$model->assigned : "",
		),
		array(
	        'label'=>'Tipo',
	        'value'=>$model->humanTypeValues[$model->type],
		),
		'capitulo',
		array(
	        'label'=>'Estat',
	        'value'=>$model->humanStateValues[$model->state],
		),
	),
));?>

<?php
	echo '<h1 style="margin-top:10px">'.$model->title.'</h1>';
	//echo $model->body;
?>


<?php echo $this->renderPartial('//consulta/_view', array('model'=>$model)); ?>





