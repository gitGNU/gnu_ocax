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
	.clear{clear:both;}
</style>
<script>
function subscribe(el){
	if('1' == '<?php echo Yii::app()->user->isGuest;?>'){
		$(el).attr('checked', false);
		alert('Please login to subscribe');
		return;
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/consulta/subscribe',
		type: 'POST',
		async: false,
		dataType: 'json',
		data: { 'consulta': <?php echo $model->id;?> },
		//beforeSend: function(){ },
		//complete: function(){ },
		//success: function(data){ },
		error: function() { alert("error on subscribe"); },
	});
}
</script>

<div style="float:right; text-align:right; padding-left:10px; padding-bottom:0px; margin:0px;">
<?php
	$criteria = new CDbCriteria;
	$criteria->condition = 'consulta = '.$model->id.' AND user = '.Yii::app()->user->getUserID();
	$checked = '';
	if( ConsultaSubscribe::model()->findAll($criteria) )
			$checked = 'checked';
?>
Mantenme informado por correo cuando hayan cambios.
<input type="checkbox" onClick="js:subscribe(this);" <?php echo $checked; ?>/>

<div class="view" style="padding:5px; text-align:left;">
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
if($model->budget){
	echo '<div style="margin-top:10px;"><b>Concept presupuestario</b>';
	$budget=Budget::model()->findByPk($model->budget);
	$this->renderPartial('//budget/_consultaView', array('model'=>$budget));
	echo '</div>';
}
?>
</div>
</div>

<div style="font-size:1.5em; text-align:center;letter-spacing:3px;">Consulta</div>
<p>

<?php
if($model->state == 0 && $model->user == Yii::app()->user->getUserID()){
	echo '<div style="margin-top:5px;font-style:italic;">Puedes '.CHtml::link('editar la consulta',array('consulta/edit','id'=>$model->id)).' y incluso ';
	echo CHtml::link('borrarla',"#",
                    array(
						"submit"=>array('delete', 'id'=>$model->id),
						"params"=>array('returnUrl'=>Yii::app()->request->baseUrl.'/user/panel'),
						'confirm' => '¿Estás seguro?'));
	echo ' hasta que la '.Config::model()->findByPk('siglas')->value.' reconozca la entrega.</div>';
}
?>
</p>

<?php echo '<h1>'.$model->title.'</h1>';?>
<?php echo $this->renderPartial('_view', array('model'=>$model,'respuestas'=>$respuestas)); ?>

<div class="clear"></div>


