<?php
/* @var $this FileController */
/* @var $model File */
/* @var $form CActiveForm */
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

?>
<script>
function validateFileName(form){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/validateFileName',
		type: 'GET',
		async: false,
		data: {	'file_name'	: $('#File_file').val().replace('C:\\fakepath\\', ''),
				'model' 	: $('#File_model').val(),
				'model_id' 	: $('#File_model_id').val(),
		},
		//beforeSend: function() {},
		success: function(data){
			if(data == 1){
				$('#file-form').hide();
				$('#loading').show(); 
				$(form).submit();
			}else
				$("#file_error").html(data);
		},
		error: function() {
			alert("Error on validate file name");
		}
	});
}
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'file-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<div class="title"><?php echo __('Upload file for').' '.$model->model;?></div>

	<?php echo $form->hiddenField($model,'model'); ?>
	<?php echo $form->hiddenField($model,'model_id'); ?>

	<?php if($model->model == 'Reply' || $model->model == 'Enquiry'){
		echo $form->label($model, 'name');
		echo '<div class="hint">'.__('Name used for the link').'</div>';
		echo $form->textField($model, 'name');
		echo $form->label($model, 'file');
	}?>

	<?php echo $form->fileField($model, 'file'); ?>
	<div class="errorMessage" id="file_error"></div>

	<div class="row buttons">
		<input type="button" value="<?php echo __('Upload')?>" onClick="js:validateFileName($('#file-form'));" />
	</div>

<?php $this->endWidget(); ?>

<div id="loading" style="display:none;text-align:center;">
<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/big_loading.gif" />
</div>

</div><!-- form -->



