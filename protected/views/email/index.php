<?php
/* @var $this EmailController */
/* @var $dataProvider CActiveDataProvider */

if($menu == 'team'){
	$this->menu=array(
		array('label'=>'View consulta', 'url'=>array('/consulta/teamView', 'id'=>$consulta->id)),
		array('label'=>'Update state', 'url'=>array('/consulta/update', 'id'=>$consulta->id)),
		array('label'=>'Add reply', 'url'=>array('/respuesta/create?consulta='.$consulta->id)),
		array('label'=>'Edit consulta', 'url'=>array('/consulta/edit', 'id'=>$consulta->id)),
		array('label'=>'List consultas', 'url'=>array('/consulta/managed')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
	);
}
if($menu == 'manager'){
	$this->menu=array(
		array('label'=>'View consulta', 'url'=>array('/consulta/adminView', 'id'=>$consulta->id)),
		array('label'=>'Manage consulta', 'url'=>array('/consulta/manage', 'id'=>$consulta->id)),
		array('label'=>'List consultas', 'url'=>array('/consulta/admin')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
	);
}
?>

Emails enviados desde la consulta <h1><?php echo $consulta->title;?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
