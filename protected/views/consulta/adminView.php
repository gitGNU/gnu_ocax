<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

$this->menu=array(
	//array('label'=>'Ver consulta', 'url'=>array('/consulta/adminView', 'id'=>$model->id)),
	array('label'=>'Manage consulta', 'url'=>array('manage', 'id'=>$model->id)),
	array('label'=>'Sent emails', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'manager')),
	
	array('label'=>'List all', 'url'=>array('admin')),
	//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
/*
if($model->state == 0){
	$deleteConsulta = array( array('label'=>'Delete consulta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),);
	array_splice( $this->menu, 1, 0, $deleteConsulta );
}else
*/
	$deleteConsulta = array( array('label'=>'Delete consulta', 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showConsulta('.$model->id.')')));
	array_splice( $this->menu, 1, 0, $deleteConsulta );

?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<style>           
	.bClose{
		cursor: pointer;
		position: absolute;
		right: -21px;
		top: -21px;
	}
</style>
<script>
function showConsulta(consulta_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/consulta/getConsultaForTeam/'+consulta_id,
		type: 'GET',
		async: false,
		dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#consulta_body").html(data.html);
				$('#mega_delete_button').attr('consulta_id', consulta_id)
				$('#consulta').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show consulta");
		}
	});
}
function megaDelete(el){
	consulta_id = $(el).attr('consulta_id');
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/consulta/megaDelete/'+consulta_id,
		type: 'POST',
		async: false,
		dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				location.href='<?php echo Yii::app()->request->baseUrl; ?>/consulta/admin';
			}else
				$('#consulta').bPopup().close();
		},
		error: function() {
			alert("Error on megaDelete");
		}
	});

}
</script>

<?php echo $this->renderPartial('_teamView', array('model'=>$model,'respuestas'=>$respuestas)); ?>

<?php if(Yii::app()->user->hasFlash('prompt_email')):?>
    <div class="flash_prompt">
        
		<p style="margin-top:5px;">Enviar un correo a las <b><?php echo Yii::app()->user->getFlash('prompt_email');?></b> personas suscritas a esta consulta?</p>
		<?php 
		$url=Yii::app()->request->baseUrl.'/email/create?consulta='.$model->id.'&menu=team';
		?>
			<button onclick="js:window.location='<?php echo $url?>';">Sí</button>
			<button onclick="$('.flash_prompt').slideUp('fast')">No</button>
    </div>
<?php endif; ?>
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

<?php echo $this->renderPartial('_megaDelete'); ?>


