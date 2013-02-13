<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

//http://stackoverflow.com/questions/10188391/yii-renderpartial-proccessoutput-true-avoid-duplicate-js-request
if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
}
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 60%; float: left;  margin: 0px;}
	.right{width: 39%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="outer">
<div class="left">
<p>
<span style="font-weight:bold">Consulta:</span><br />
<?php
if($model->state == 0 && $model->user == Yii::app()->user->getUserID()){
	echo '<span>Puedes '.CHtml::link('editar la consulta',array('consulta/edit','id'=>$model->id)).' y incluso ';
	//$url = '/consulta/delete';
	echo CHtml::link('borrarla',"#",
                    array(
						"submit"=>array('delete', 'id'=>$model->id),
						"params"=>array('returnUrl'=>Yii::app()->request->baseUrl.'/user/panel'),
						'confirm' => '¿Estás seguro?'));
	echo ' hasta que la OCA(x) reconozca la entrega</span>';
}
?>
</p>

<?php echo '<h1>'.$model->title.'</h1>';?>

</div>
<div class="right">

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'created',
		array(
	        'label'=>'Tipo',
	        'value'=>$model->humanTypeValues[$model->type],
		),
		array(
	        'label'=>'Estat',
	        'value'=>$model->humanStateValues[$model->state],
		),
	),
));
?>

</div>
</div>
<div class="clear"></div>

<?php echo $this->renderPartial('_view', array('model'=>$model,'respuestas'=>$respuestas)); ?>


