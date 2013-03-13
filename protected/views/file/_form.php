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
		data: {	'file_name'	: $('#File_file').val(),
				'model' 	: $('#File_model').val(),
				'model_id' 	: $('#File_model_id').val(),
		},
		//beforeSend: function(){ /*alert();*/ },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data == 1)
				$(form).submit();
			else
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

	<div class="title">Upload file for <?php echo $model->model;?></div>

	<?php echo $form->hiddenField($model,'model'); ?>
	<?php echo $form->hiddenField($model,'model_id'); ?>

	<?php echo $form->fileField($model, 'file'); ?>
	<div class="errorMessage" id="file_error"></div>

	<div class="row buttons">
		<input type="button" value="Upload" onClick="js:validateFileName($('#file-form'));" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
