
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
		array(
	        'label'=>'Estat',
	        'value'=>$model->getHumanStates($model->state),
		),
	),
));?>

<?php
if($model->budget){
	$budget=Budget::model()->findByPk($model->budget);
	echo $this->renderPartial('//budget/_consultaView', array('model'=>$budget));
}
?>

<?php echo '<h1 style="margin-top:10px">'.$model->title.'</h1>';?>
<?php echo $this->renderPartial('//consulta/_view', array('model'=>$model)); ?>








