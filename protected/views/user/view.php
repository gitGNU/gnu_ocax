<?php
/* @var $this UserController */
/* @var $model User */

$this->menu=array(
	array('label'=>'Change User\'s roles', 'url'=>array('updateRoles', 'id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<h1>Usuario: <?php echo $model->username; ?></h1>
<p>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'fullname',
		'email',
		'joined',
		'is_socio',
		'is_team_member',
		'is_editor',
		'is_manager',
		'is_admin',
	),
)); ?>
</p>
<?php
if($consultas->getData()){
echo '<p style="font-size:1.5em">Consultas de '.$model->fullname.'</p>';
$this->widget('PGridView', array(
	'id'=>'consulta-grid',
	'dataProvider'=>$consultas,
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/consulta/view',
    ),
	'ajaxUpdate'=>true,
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
			array(
				'header'=>'Consultas',
				'name'=>'title',
				'value'=>'$data[\'title\']',
			),
			'created',
			array(
				'header'=>'Estat',
				'name'=>'state',
				'type' => 'raw',
				'value'=>'$data->humanStateValues[$data[\'state\']]',
			),
            array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
}else
echo '<p style="font-size:1.5em">'.$model->fullname.' no ha hecho ninguna consulta</p>';
?>
