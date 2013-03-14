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
.socialIcons {	margin:0px; }
.socialIcons img { cursor:pointer; }
#directlink span  { cursor:pointer; }
#directlink span:hover { color:black; }
</style>
<script>
function subscribe(el){
	if('1' == '<?php echo Yii::app()->user->isGuest;?>'){
		$(el).attr('checked', false);
		alert('Please login to subscribe');
		$('#subscribe').hide();
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
		success: function(data){
			$('#subscribe').fadeOut();
		},
		error: function() { alert("error on subscribe"); },
	});
}
function toggleSocialPopup(id){
	if ( $('#'+id).is(':visible') )
		$('#'+id).hide();
	else{
		$('.social_popup').hide();
		$('#'+id).show();
	}
}
</script>

<div style="float:right; text-align:right; padding-left:10px; padding-bottom:0px; margin:0px; margin-top:0px">
<?php
	$criteria = new CDbCriteria;
	$criteria->condition = 'consulta = '.$model->id.' AND user = '.Yii::app()->user->getUserID();
	$checked = '';
	if( ConsultaSubscribe::model()->findAll($criteria) )
			$checked = 'checked';
?>

<div class="view" style="padding:5px; text-align:left;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'created',
		array(
	        'label'=>__('Type'),
	        'value'=>$model->humanTypeValues[$model->type],
		),
		array(
	        'label'=>__('State'),
			'type' => 'raw',
	        'value'=>$model->getHumanStates($model->state),

		),
	),
));
if($model->budget){
	$budget=Budget::model()->findByPk($model->budget);
	$this->renderPartial('//budget/_consultaView', array('model'=>$budget));
	echo '</div>';
}
?>
</div>
</div>

<div class="socialIcons">
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/link.png" onClick="js:toggleSocialPopup('directlink');"/>
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/mail.png" onClick="js:toggleSocialPopup('subscribe');"/>
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/facebook.png" />
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/twitter.png" />
</div>

<div id="subscribe" style="	display :none;
							position: absolute;
							padding:5px;
							z-index: 1;
							width: 400px;
							background-color: #98FB98;
							"
	class="social_popup">
Mantenme informado por correo cuando hayan cambios.
<input type="checkbox"	onClick="js:subscribe(this);"
						style="
						    vertical-align: middle;
						    position: relative;
						    bottom: 1px;
						"
	<?php echo $checked; ?>
/>
</div>

<div id="directlink" style="display :none;
							position: absolute;
							padding:5px;
							z-index: 1;
							width: 400px;
							background-color: #98FB98;
							"
	class="social_popup">
<?php
	$url = $this->createAbsoluteUrl('/consulta/'.$model->id);
	//$url=Yii::app()->request->baseUrl.'/consulta/'.$model->id;
	echo '<span onClick=\'location.href="'.$url.'";\'>'.$url.'</span>';
?>
</div>

<?php
if($model->state == 0 && $model->user == Yii::app()->user->getUserID()){
	echo '<div style="font-style:italic;">Puedes '.CHtml::link('editar la consulta',array('consulta/edit','id'=>$model->id)).' y incluso ';
	echo CHtml::link('borrarla',"#",
                    array(
						"submit"=>array('delete', 'id'=>$model->id),
						"params"=>array('returnUrl'=>Yii::app()->request->baseUrl.'/user/panel'),
						'confirm' => '¿Estás seguro?'));
	echo ' hasta que la '.Config::model()->findByPk('siglas')->value.' reconozca la entrega. (+ comments and subscriptions).</div>';
}
?>

<?php echo '<h1 style="margin-top:15px;">'.$model->title.'</h1>';?>
<?php echo $this->renderPartial('_view', array('model'=>$model,'respuestas'=>$respuestas)); ?>

<div class="clear"></div>


