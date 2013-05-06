<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

$this->menu=array(
	array('label'=>__('View enquiry'), 'url'=>array('teamView', 'id'=>$model->id)),
	array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
	array('label'=>__('List all'), 'url'=>array('managed')),
);
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<script>
function reject(){
	$('#Enquiry_state').val('<?php echo ENQUIRY_REJECTED;?>');
	$('#enquiry-form').submit();
}
function validate(){
	$('#Enquiry_state').val('<?php echo ENQUIRY_ACCEPTED;?>');
	$('#enquiry-form').submit();
}
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->hiddenField($model, 'state');?>

	<div class="title"><?php echo __('Validate enquiry');?></div>

<div class="outer">

	<?php echo $form->errorSummary($model); ?>

<div class="left">
	<div class="row buttons">
		<b><?php echo __('Accept the Enquiry');?></b>
		<div class="hint"><?php echo __('You accept this enquiry as valid');?></div>
		<p style="margin-bottom:37px"></p>
		<?php echo CHtml::button(__('Accept'),array('onclick'=>'js:validate();')); ?>
	</div>

</div>
<div class="right">
	<div class="row buttons">
		<b><?php echo __('Reject the Enquiry');?></b>
		<div class="hint"><?php echo __('The enquiry is inappropriate');?></div>
		<p style="margin-bottom:37px"></p>
		<?php echo CHtml::button(__('Reject'),array('onclick'=>'js:reject();')); ?>
	</div>
</div>
</div>
<div class="clear"></div>

<?php $this->endWidget(); ?>
</div><!-- form -->



<p></p>
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>

<?php if(Yii::app()->user->hasFlash('prompt_email')):?>
    <div class="flash_prompt">
		<p style="margin-top:5px;">Enviar un correo a las <b><?php echo Yii::app()->user->getFlash('prompt_email');?></b> personas suscritas a esta enquiry?</p>
		<?php 
		$url=Yii::app()->request->baseUrl.'/email/create?enquiry='.$model->id.'&menu=team';
		?>
			<button onclick="js:window.location='<?php echo $url?>';">Sí</button>
			<button onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?>/enquiry/managed';">No</button>
    </div>
<?php endif; ?>
