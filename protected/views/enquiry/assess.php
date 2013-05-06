<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

$this->menu=array(
	array('label'=>__('View enquiry'), 'url'=>array('/enquiry/teamView', 'id'=>$model->id)),
	array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
	array('label'=>__('List enquiries'), 'url'=>array('/enquiry/managed')),
);

?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<script>
function positive(){
	$('#Enquiry_state').val('<?php echo ENQUIRY_REPLY_SATISFACTORY;?>');
	$('#enquiry-form').submit();
}
function negative(){
	$('#Enquiry_state').val('<?php echo ENQUIRY_REPLY_INSATISFACTORY;?>');
	$('#enquiry-form').submit();
}
</script>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->hiddenField($model, 'state');?>
	<div class="title"><?php echo __('Assess reply')?></div>

<div class="outer">

<div class="left">
	<div class="row buttons">
		<b><?php echo __('Reply considered satisfactory');?></b>
		<div class="hint"><?php echo __('The general consensus is that the reply is satisfactory');?></div>
		<p style="margin-bottom:37px"></p>
		<?php echo CHtml::button(__('Reply considered satisfactory'),array('onclick'=>'js:positive();')); ?>
	</div>

</div>
<div class="right">
	<div class="row buttons">
		<b><?php echo __('Reply considered insatisfactory');?></b>
		<div class="hint"><?php echo __('The general consensus is that the reply is not satisfactory');?></div>
		<p style="margin-bottom:37px"></p>
		<?php echo CHtml::button(__('Reply considered insatisfactory'),array('onclick'=>'js:negative();')); ?>
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






