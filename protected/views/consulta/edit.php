<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

$this->menu=array(
	array('label'=>'Ver Consulta', 'url'=>array('/consulta/teamView', 'id'=>$model->id)),
	array('label'=>'Actualizar estat', 'url'=>array('/consulta/update', 'id'=>$model->id)),
	array('label'=>'Anadir respuesta', 'url'=>array('/respuesta/create?consulta='.$model->id)),
	array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
	array('label'=>'Listar consultas', 'url'=>array('/consulta/managed')),
	//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
?>

<div class="consulta">
<h1>Editar consulta</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>


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
$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));
foreach($respuestas as $respuesta){
	echo '<hr>';
	echo '<p>';
	echo '<b>Respuesta: '.date_format(date_create($respuesta->created), 'Y-m-d').'</b><br />';
	echo '<p>'.$respuesta->body.'</p>';
	echo '</p>';
}?>
</div>

