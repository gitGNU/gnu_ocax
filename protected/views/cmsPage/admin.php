<?php
/* @var $this CmsPageController */
/* @var $model CmsPage */

$this->menu=array(
	array('label'=>__('Create CmsPage'), 'url'=>array('create')),
	array('label'=>__('Show uploaded files'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showUploadedFiles();')),
	array('label'=>__('Upload file'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:uploadFile();')),
);
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>

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
function uploadFile(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/create?model=<?php echo get_class($model);?>',
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
			alert("Error on get file/create");
		}
	});
}
</script>

<h1><?php echo __('Manage Cms Pages')?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->theme->baseUrl.'/css/pgridview.css',
	'id'=>'cms-page-grid',
	'selectableRows'=>1,
	'selectionChanged'=>'function(id){ location.href = "'.$this->createUrl('update').'/"+$.fn.yiiGridView.getSelection(id);}',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header'=>__('Page name'),
			'value'=>'CmsPage::model()->getTitleForModel($data->id)',
		),
		'block',
		'weight',
		'published',
	),
)); ?>

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 2750);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('success');?></b></p>
    </div>
<?php endif; ?>

<div id="files" style="display:none;width:500px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="files_content" style="background-color:white;"></div>
</div>
