<?php
/* @var $this EmailController */
/* @var $dataProvider CActiveDataProvider */

if($menu == 'team'){
	$this->menu=array(
		array('label'=>'Ver Consulta', 'url'=>array('/consulta/teamView', 'id'=>$consulta->id)),
		array('label'=>'Actualizar estat', 'url'=>array('/consulta/update', 'id'=>$consulta->id)),
		array('label'=>'Anadir respuesta', 'url'=>array('/respuesta/create?consulta='.$consulta->id)),
		array('label'=>'Editar Consulta', 'url'=>array('/consulta/edit', 'id'=>$consulta->id)),
		array('label'=>'Listar consultas', 'url'=>array('/consulta/managed')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
	);
}
if($menu == 'manager'){
	$this->menu=array(
		array('label'=>'Ver Consulta', 'url'=>array('/consulta/adminView', 'id'=>$consulta->id)),
		array('label'=>'Gestionar consulta', 'url'=>array('/consulta/manage', 'id'=>$consulta->id)),
		array('label'=>'Listar consultas', 'url'=>array('/consulta/admin')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
	);
}
?>

<h1>Emails a <?php echo $consulta->user0->fullname;?></h1>

<p style="font-size:1.5em">Consulta: <?php echo $consulta->title;?></p>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
