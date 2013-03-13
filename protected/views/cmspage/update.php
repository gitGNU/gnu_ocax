<?php
/* @var $this CmspageController */
/* @var $model CmsPage */

$this->menu=array(
	array('label'=>'Show uploaded files', 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showUploadedFiles();')),
	array('label'=>'View CmsPage', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CmsPage', 'url'=>array('admin')),
);
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
function showUploadedFiles(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/showCMSfiles',
		type: 'POST',
		async: false,
		//dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#files_content").html(data);
				$('#files').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on showCMSfiles");
		}
	});
}
</script>

<?php echo $this->renderPartial('_form', array('model'=>$model,'title'=>'Update \''.$model->pagename.'\'')); ?>

<div id="files" style="display:none;width:500px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="files_content" style="background-color:white;"></div>
</div>

