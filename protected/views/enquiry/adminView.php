<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

$this->menu=array(
	//array('label'=>'Ver enquiry', 'url'=>array('/enquiry/adminView', 'id'=>$model->id)),
	array('label'=>'Manage enquiry', 'url'=>array('manage', 'id'=>$model->id)),
	array('label'=>'Sent emails', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'manager')),
	
	array('label'=>'List all', 'url'=>array('admin')),
	//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
/*
if($model->state == 0){
	$deleteEnquiry = array( array('label'=>'Delete enquiry', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),);
	array_splice( $this->menu, 1, 0, $deleteEnquiry );
}else
*/
	$deleteEnquiry = array( array('label'=>'Delete enquiry', 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showEnquiry('.$model->id.')')));
	array_splice( $this->menu, 1, 0, $deleteEnquiry );

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
function showEnquiry(enquiry_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/getMegaDelete/'+enquiry_id,
		type: 'GET',
		async: false,
		//dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#mega_delete_content").html(data);
				$('#mega_delete_button').attr('enquiry_id', enquiry_id)
				$('#mega_delete').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show mega delete");
		}
	});
}
function megaDelete(el){
	enquiry_id = $(el).attr('enquiry_id');
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/megaDelete/'+enquiry_id,
		type: 'POST',
		async: false,
		//data: { 'id' : enquiry_id },
		//dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			$('#mega_delete').bPopup().close();
			if(data != 0){
				$('#enquirys-grid').yiiGridView('update');
			}
		},
		error: function() {
			alert("Error on megaDelete");
		}
	});

}
</script>

<?php echo $this->renderPartial('_teamView', array('model'=>$model,'replys'=>$replys)); ?>

<?php if(Yii::app()->user->hasFlash('prompt_email')):?>
    <div class="flash_prompt">
        
		<p style="margin-top:5px;">Enviar un correo a las <b><?php echo Yii::app()->user->getFlash('prompt_email');?></b> personas suscritas a esta enquiry?</p>
		<?php 
		$url=Yii::app()->request->baseUrl.'/email/create?enquiry='.$model->id.'&menu=team';
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


<div id="mega_delete" style="display:none;width:850px;">
	<div style="background-color:white;padding:5px;">
		<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
		<div id="mega_delete_content"></div>
	</div>
</div>


