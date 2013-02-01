<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

$this->menu=array(
	//array('label'=>'Ver consulta', 'url'=>array('/consulta/adminView', 'id'=>$model->id)),
	array('label'=>'Gestionar consulta', 'url'=>array('manage', 'id'=>$model->id)),
	array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'manager')),
	array('label'=>'Listar todas', 'url'=>array('admin')),
	//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
?>

<?php if(Yii::app()->user->hasFlash('prompt')):?>
    <div class="flash_prompt">
        <?php /*echo Yii::app()->user->getFlash('success');*/ ?>
		<p style="margin-top:5px;"><b>Enviar un correo a <?php echo $model->user0->fullname;?>?</b></p>
		<?php 
		$url=Yii::app()->request->baseUrl.'/email/create?consulta='.$model->id.'&menu=manager';
		?>
			<button onclick="js:window.location='<?php echo $url?>';">Sí</button>
			<button onclick="$('.flash_prompt').slideUp('fast')">No</button>
    </div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 2000);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:5px;"><b>Email enviado correctamente</b></p>
    </div>
<?php endif; ?>

<div class="consulta">
<?php echo $this->renderPartial('_teamView', array('model'=>$model,'respuestas'=>$respuestas)); ?>
</div>



