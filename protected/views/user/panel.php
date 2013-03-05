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

<?php if(!$model->is_active){
	echo '<h1>Welcome</h1>';
	$this->renderPartial('_notActiveInfo', array('model'=>$model));
}?>

<div class="outer">
<div class="left">
<h1><?php echo CHtml::link('Nueva consulta',array('consulta/create/'));?></h1>
<p>
Tanto genéricas como las presupuestarias,
realizar una nueva consulta sobre la actividad de tu ayuntamiento y nosotros haremos la gestión.<br />
</p>
</div>
<div class="right">
<h1><?php echo CHtml::link('Mis datos de usuario',array('user/update/'));?></h1>
<p>
Change your profile<br />
Configure your email<br />
Change your password</p>
</div>
<?php

if($model->is_team_member){
	changeColumn();
	echo '<h1>'.CHtml::link('Consultas encomendadas',array('consulta/managed')).'</h1>';
	echo '<p>Manage the consultas you are responsable for.</p>';
	echo "</div>";
}

if($model->is_editor){
	changeColumn();
	echo '<h1>'.CHtml::link('Site CMS page editor',array('/cmspage')).'</h1>';
	echo '<p>Edit the general information pages</p>';
	echo '</div>';
}

if($model->is_manager){
	changeColumn();
	echo '<h1>'.CHtml::link('Manage consultas',array('consulta/admin')).'</h1>';
	echo '<p>Assign new consultas a team members y check status.</p>';
	echo '</div>';
}

if($model->is_admin){
	changeColumn();
	echo '<h1>Administator\'s options</h1>';
	echo 'Budgets: '.CHtml::link('Years and budget data',array('budget/adminYears')).'<br />';
	echo 'Users: '.CHtml::link('Admin users and roles',array('user/admin')).'<br />';
	echo 'Default emails: '.CHtml::link('Define texts to send via email',array('emailtext/admin')).'<br />';
	echo 'Global parameters: '.CHtml::link('Edit global parameters',array('config/admin')).'<br />';
	echo '</div>';
}

?>

</div>
<div class="clear"></div>

<?php
if($consultas->getData()){
echo '<div style="font-size:1.5em">Mis consultas</div>';
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
}
?>

<?php

if($subscribed->getData()){
echo '<div style="font-size:1.5em">Estoy suscrito a estas consultas</div>';
echo '<span class="hint">Se te enviará un correo cuando se actualicen estas consultas</span>';
$this->widget('PGridView', array(
	'id'=>'subscribed-grid',
	'dataProvider'=>$subscribed,
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
				//'value' => 'data[\'state\']',
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
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('success');?></b></p>
    </div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('newActivationCodeError')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_prompt').fadeOut('fast');
    	}, 1750);
		});
	</script>
    <div class="flash_prompt">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('newActivationCodeError');?></b></p>
    </div>
<?php endif; ?>

