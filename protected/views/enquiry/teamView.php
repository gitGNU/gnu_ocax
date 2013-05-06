<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

$this->menu=array(
	array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
	array('label'=>__('List enquiries'), 'url'=>array('/enquiry/managed')),
);
if($model->state == ENQUIRY_ACCEPTED){
	$submit = array( array('label'=>__('Submit enquiry'), 'url'=>array('/enquiry/submitted', 'id'=>$model->id)) );
	array_splice( $this->menu, 0, 0, $submit );
}
if($model->state < ENQUIRY_AWAITING_REPLY){
	$edit = array( array('label'=>__('Edit enquiry'), 'url'=>array('/enquiry/edit', 'id'=>$model->id)) );
	array_splice( $this->menu, 0, 0, $edit );
}
if($model->state == ENQUIRY_ASSIGNED){
	$validate = array( array('label'=>__('Accept / Reject'), 'url'=>array('/enquiry/validate', 'id'=>$model->id)) );
	array_splice( $this->menu, 0, 0, $validate );
}
if($model->state >= ENQUIRY_AWAITING_REPLY){
	$reply = array( array('label'=>__('Add reply'), 'url'=>array('/reply/create?enquiry='.$model->id)) );
	array_splice( $this->menu, 0, 0, $reply );
}
if($model->state == ENQUIRY_REPLY_PENDING_ASSESSMENT){
	$assess = array( array('label'=>__('Assess reply'),  'url'=>array('/enquiry/assess', 'id'=>$model->id)) );
	array_splice( $this->menu, 0, 0, $assess );
}
if($model->state > ENQUIRY_REPLY_PENDING_ASSESSMENT){
	$reformulate = array( array('label'=>__('Reformulate enquiry'), 'url'=>array('/enquiry/create?related='.$model->id))  );
	array_splice( $this->menu, 0, 0, $reformulate );
}
?>

<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>

<?php if(Yii::app()->user->hasFlash('prompt_email')):?>
    <div class="flash_prompt">
        
		<p style="margin-top:5px;"><?php echo __('Send an email to the');?>
		<b><?php echo Yii::app()->user->getFlash('prompt_email');?></b>
		<?php echo __('people subscribed to the Enquiry')?>
		?</p>
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






