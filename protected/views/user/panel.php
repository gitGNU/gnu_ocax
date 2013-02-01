<?php
/* @var $this UserController */
/* @var $model User */
/*
 * @property integer $is_team_member
 * @property integer $is_editor
 * @property integer $is_manager
 * @property integer $is_admin
*/


$column=0;
function changeColumn()
{
	global $column;
	if($column==0)
	{
		echo '<div class="clear"></div>';
		echo '<div class="left">';
		$column=1;
	}
	else
	{
		echo '<div class="right">';
		$column=0;
	}
}
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>


<div class="outer">
<div class="left">
<h1><?php echo CHtml::link('Nueva consulta',array('consulta/create/'));?></h1>
<p>
Tanto genéricas como las presupuestarias,
realizar una nueva consulta sobre la actividad de tu ayuntamiento y nosotros haremos la gestión.<br />
</p>
</div>
<div class="right">
<h1><?php echo CHtml::link('Tus datos de usuario',array('user/update/'));?></h1>
<p>
Change your profile<br />
Configure your email<br />
Change your password</p>
</div>
<?php

if($model->is_team_member){
	changeColumn();
	echo '<h1>'.CHtml::link('Consultas encomendadas',array('consulta/managed')).'</h1>';
	echo '<p>Gestionar las consultas que te han encargado.</p>';
	echo "</div>";
}

if($model->is_editor){
	changeColumn();
	echo '<h1>'.CHtml::link('Site CMS page editor',array('/cmspage')).'</h1>';
	echo '<p>Edit the general information pages</p>';
	echo "</div>";
}

if($model->is_manager){
	changeColumn();
	echo '<h1>'.CHtml::link('Gestionar consultas',array('consulta/admin')).'</h1>';
	echo '<p>Asignar nuevas consultas a miembros del equipo y comprobar el estado de todos las consultas.</p>';
	echo "</div>";
}

if($model->is_admin){
	changeColumn();
	echo '<h1>'.CHtml::link('Admin usuarios',array('user/admin')).'</h1>';
	echo "<p>Change user roles<br />Delete users</p>";
	echo "</div>";
}

?>

</div>
<div class="clear"></div>

<?php
if($consultas->getData()){
$this->widget('PGridView', array(
	'id'=>'consulta-grid',
	'dataProvider'=>$consultas,
    'onClick'=>array(
        'type'=>'url',
        'call'=>'/ocax/consulta/view',
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
}
?>

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 1750);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:25px;"><b>Cambios guardados correctamente</b></p>
    </div>
<?php endif; ?>


