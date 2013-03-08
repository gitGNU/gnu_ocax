<?php
/* @var $this UserController */
/* @var $model User */

$this->menu=array(
	array('label'=>'Cambiar user\'s roles', 'url'=>array('updateRoles', 'id'=>$model->id)),
	array('label'=>'Delete user', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Todos los usuarios', 'url'=>array('admin')),
);
?>


<div class="form">
<div class="title">username: <?php echo $model->username; ?></div>
<div class="row" style="margin:-15px -10px -10px -10px;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
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
</div>
</div>
<p></p>
<?php
if($consultas->getData()){
echo '<span style="font-size:1.5em">Consultas made by '.$model->fullname.'</span>';
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
				'value'=>'$data->getHumanStates($data[\'state\'])',
			),
            array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
}else
echo '<p style="font-size:1.5em">'.$model->fullname.' no ha hecho ninguna consulta</p>';
?>
