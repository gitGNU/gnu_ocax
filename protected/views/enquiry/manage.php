<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

$this->menu=array(
	array('label'=>'View enquiry', 'url'=>array('adminView', 'id'=>$model->id)),
	array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'manager')),
	array('label'=>__('List all'), 'url'=>array('admin')),
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
	$('#Enquiry_state').val('rejected');
	$('#enquiry-form').submit();
}
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title"><?php echo __('Manage enquiry');?></div>

<div class="outer">
<div class="left">

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model, 'state');?>

	<div class="row">
		<?php /*echo $form->label($model,'type');*/ ?>
		<?php /*echo $form->dropDownList($model, 'type', $model->humanTypeValues);*/ ?>
		<?php /*echo $form->error($model,'type');*/ ?>
	</div>

	<div class="row">
		<?php /* echo $form->labelEx($model,'capitulo'); */?>
		<?php /*echo $form->textField($model,'capitulo'); */?>
		<?php /*echo $form->error($model,'capitulo'); */?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'team_member'); ?>
		<div class="hint"><?php echo __('Team member responsable for this Enquiry');?></div>
		<?php
			$data=CHtml::listData($team_members,'id', 'fullname');
			echo $form->dropDownList($model, 'team_member', $data, array('prompt'=>__('Not assigned')));
		?>
		<?php echo $form->error($model,'team_member'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(__('Assign')); ?>
	</div>

</div>
<div class="right">

	<div class="row buttons">
		<b><?php echo __('Reject the Enquiry');?></b>
		<div class="hint"><?php echo __('The enquiry is inappropriate');?></div>
		<p style="margin-bottom:37px"></p>
		<?php echo CHtml::button(__('Reject'),array('onclick'=>'reject()')); ?>
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
		$url=Yii::app()->request->baseUrl.'/email/create?enquiry='.$model->id.'&menu=manager';
		?>
			<button onclick="js:window.location='<?php echo $url?>';">Sí</button>
			<button onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?>/enquiry/admin';">No</button>
    </div>
<?php endif; ?>
