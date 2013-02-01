<?php
/* @var $this CommentController */
/* @var $model Comment */
/* @var $form CActiveForm */
?>

<script>

</script>

<div class="view" style="text-align:left";>
<b><?php echo $fullname;?></b> comenta ..</br />
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'action'=>'',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>false,
)); ?>
	<?php echo $form->hiddenField($model,'consulta');?>
	<?php echo $form->hiddenField($model,'respuesta');?>

	<div class="row">
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>80)); ?>
	</div>

	<div class="row" style="text-align:right";>
		<?php /*echo CHtml::submitButton($model->isNewRecord ? 'Publicar' : 'Save'); */ ?>


		<input type="button" onClick="js:submitComment($(this).parents('form:first'));" value="Publicar" />

<?php
/*
		echo CHtml::ajaxSubmitButton('Publicar', Yii::app()->request->baseUrl.'/comment/create',
		array(
			'type'=>'POST',
			'dataType'=>'json',
			'data'=>'$("#comment-form").serialize()',
			'beforeSend'=>'function(){ alert($("#comment-form").serialize()); }',
			'success'=>'js:function(data){
				if(data != 0){
					alert(data);
					$("#comment_form").after(data.html);
					$("#comment_form").hide();
				}
			}',
			'error'=>'js:function() { alert("error on create comment"); }',
		));
*/
?>
		<input type="button" onClick="js:cancelComment();" value="Cancelar" />
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->

