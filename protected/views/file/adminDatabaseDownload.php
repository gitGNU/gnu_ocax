<?php
/* @var $this FileController */
/* @var $model File */

$this->menu=array(
	array('label'=>__('Add file to docs'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:uploadFile();')),
	array('label'=>__('Add csv to data'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showYears();')),
	array('label'=>__('Update zip file'), 'url'=>array('file/createZipFile')),
	array('label'=>'List Years', 'url'=>array('budget/adminYears')),
);
if($csv_file=File::model()->findByAttributes(array('model'=>'DatabaseDownload'))){
	$download = array( array('label'=>__('Download zip file'), 'url'=>$csv_file->webPath));
	array_splice( $this->menu, 3, 0, $download );
}
?>

<style>           
	.bClose{
		cursor: pointer;
		position: absolute;
		right: -21px;
		top: -21px;
	}
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script>
function uploadFile(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/create?model=DatabaseDownload/docs',
		type: 'POST',
		async: false,
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
function deleteFile(file_id){
	answer=confirm("Are you sure?");
	if(!answer)
		return 1;
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/delete/'+file_id,
		type: 'POST',
		async: false,
		success: function(){
				$("#attachment_"+file_id).remove();
		},
		error: function() {
			alert("Error on get file/delete");
		}
	});
}
function showYears(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/csv/showYears',
		type: 'GET',
		async: false,
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#csvs_content").html(data);
				$('#csvs').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show years");
		}
	});
}
function regenCSV(id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/csv/regenerateCSV/'+id,
		type: 'GET',
		async: false,
		beforeSend: function(){ $('#years-grid').replaceWith($('#loading')); $('#loading').show(); },
//		complete: function(){ location.reload(true); },
		complete: function(){},

		success: function(data){
			$('#csvs').bPopup().close();
			$.fn.yiiGridView.update('file-grid');
			//$('#file-grid').yiiGridView('update');
			if(data != 0){
				//$('#file-grid').yiiGridView('update'); // doesn't work so reload page :(
			}
		},

		error: function() {
			alert("Error on regenerate csv");
		}
	});
}
</script>

<h1><?php echo __('Prepare file').' '.File::model()->normalize(Config::model()->findByPk('siglas')->value);?>.zip</h1>

<?php
$dataProvider = new CActiveDataProvider('File', array(
    'criteria'=>array(	'condition'=>'model = "DatabaseDownload/data" OR model = "DatabaseDownload/docs"',
						'order'=>'uri ASC',
				),
));
echo '<div style="font-size:1.3em">'.__('Files ready to include in zip').'</div>';
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'file-grid',
	'dataProvider'=>$dataProvider,
	'template' => '{items}{pager}',
	'ajaxUpdate'=>true,
	'columns'=>array(
		//'webPath',
		array(
			'name'=>__('Files to include'),
			'type'=>'raw',
			'value'=> '$data->model."/".$data->name',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {delete}',
		'buttons'=>array(
			'view' => array(
				'url'=> '"javascript:location.href=\"".$data->webPath."\";"',
			),
		),
		),
	),

));
?>

<div id="csvs" style="display:none;width:500px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="csvs_content" style="background-color:white;"></div>
</div>

<div id="files" style="display:none;width:500px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="files_content" style="background-color:white;"></div>
</div>

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 2000);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('success');?></b></p>
    </div>
<?php endif; ?>

