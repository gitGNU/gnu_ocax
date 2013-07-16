<?php
/* @var $this CommentController */
/* @var $model Comment */
/* @var $form CActiveForm */
?>


<div>
<b><?php echo $fullname;?></b> <?php echo __('comments')?> ..</br />
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'action'=>'',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>false,
)); ?>
	<?php echo $form->hiddenField($model,'enquiry');?>
	<?php echo $form->hiddenField($model,'reply');?>

	<div class="row">
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>80)); ?>
	</div>

	<div class="row" style="margin-top:10px">
		<input type="button" onClick="js:submitComment($(this).parents('form:first'));" value="<?php echo __('Publish');?>" />
		<input type="button" onClick="js:cancelComment();" value="<?php echo __('Cancel');?>" />
		<img style="vertical-align:middle;display:none" class="loading_gif" src="<?php echo Yii::app()->theme->baseUrl;?>/images/loading.gif" />
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->

