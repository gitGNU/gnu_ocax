<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/* @var $this FileController */
/* @var $model File */

$this->menu=array(
	array('label'=>__('Update zip file'), 'url'=>array('file/createZipFile')),
	array('label'=>__('Add file to docs'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:uploadFile();')),
	array('label'=>__('Add csv to data'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showYears();')),
);
if($csv_file=File::model()->findByAttributes(array('model'=>'DatabaseDownload'))){
	$download = array( array('label'=>__('Download zip file'), 'url'=>$csv_file->getWebPath()));
	array_splice( $this->menu, 3, 0, $download );
}
$this->inlineHelp=':profiles:admin:zip';
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>
<script>
function uploadFile(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/create?model=DatabaseDownload/docs',
		type: 'POST',
		success: function(data){
			if(data != 0){
				$("#files_popup_content").html(data);
				$('#files_popup').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, speed: 10
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
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#csvs_popup_content").html(data);
				$('#csvs_popup').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, speed: 10
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
		beforeSend: function(){ $('#years-grid').replaceWith($('#loading')); $('#loading').show(); },
		success: function(data){
			$('#csvs_popup').bPopup().close();
			$.fn.yiiGridView.update('file-grid');
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
						'order'=>'path ASC',
				),
));
echo '<div style="font-size:1.3em">'.__('Files queued and ready to include in zip').'</div>';
$this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
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

<div id="csvs_popup" class="modal" style="width:500px;">
	<i class='icon-cancel-circled modalWindowButton bClose'></i>
	<div id="csvs_popup_content"></div>
</div>

<?php echo $this->renderPartial('//file/modal'); ?>

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash-success').slideUp('fast');
    	}, 3000);
		});
	</script>
    <div class="flash-success">
		<?php echo Yii::app()->user->getFlash('success');?>
    </div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('error')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash-error').slideUp('fast');
    	}, 3000);
		});
	</script>
    <div class="flash-error">
		<?php echo Yii::app()->user->getFlash('error');?>
    </div>
<?php endif; ?>
