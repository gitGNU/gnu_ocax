<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2015 OCAX Contributors. See AUTHORS.

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

/* @var $this ArchiveController */
/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('archive-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$userCanCreate = Yii::app()->user->isPrivileged();
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/archive.css" />

<style>
.pgrid-view table.items th
{
	font-size: 1.2em;
	color: #555;
	background-color: transparent;
	text-align: left;
}
.pgrid-view table.items td {
	font-size: 1.1em;
}
.pgrid-view i { cursor:pointer; }
</style>


<?php if($userCanCreate){ ?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>

<script>
function uploadFile(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/archive/create',
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
			alert("Error on get archive/create");
		}
	});
}
function deleteArchive(archive_id){
	if(confirm("<?php echo __('Delete this archive?');?>") == false)
		return;

	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/archive/delete/'+archive_id,
		type: 'POST',
		success: function(data){
			$.fn.yiiGridView.update("archive-grid",{});
		},
		error: function() {
			alert("Error on get archive/delete");
		}
	});
}
</script>
<?php 
	echo $this->renderPartial('//file/modal');
} ?>

<div style="margin:-15px 0 15px -15px;">
<?php
echo '<h1 style="float:left;"><i class="icon-folder-1"></i> '.__('Archive').'</h1>';
if($userCanCreate){
	echo '<div style="float:right; padding-left:20px;">';
	echo '<a class="link" href="'.getInlineHelpURL(':archive').'" target="_new">'.__('About the Archive').'</a><br />';
	echo '<span class="link" onClick="js:uploadFile()">'.__('Upload a file').'</span>';
	echo '</div>';
}
?>

<div style="float:right; white-space:nowrap;"><!-- search-form -->
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div>

<div class="clear"></div>
</div>

<div class="horizontalRule"></div>

<?php
$user_id = 0;
$is_admin = 0;
if(!Yii::app()->user->isGuest){
	$user_id = Yii::app()->user->getUserID();
	$is_admin = Yii::app()->user->isAdmin();
}

$this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
	'id'=>'archive-grid',
	'dataProvider'=>$dataProvider,
	'template' => '{items} {pager}',
	'ajaxUpdate'=>true,
	'columns'=>array(
		array(
			'type'=>'raw',
			'value'=> '$data->getIcon();',
		),
		array(
			'name'=>__('Name'),
			'value'=> '$data->name',
		),
		array(
			'name'=>__('Description'),
			'value'=> '$data->description',
		),
		array(
			'name'=>__('Date'),
			'type'=>'raw',
			'value'=> 'format_date($data->created);',
		),
		array(
			'type'=>'raw',
			'value'=> '$data->getGridActions('.$user_id.', '.$is_admin.');',
		),	
	),
));
?>
<div style="clear:both"></div>

<?php if($userCanCreate){
	if(Yii::app()->user->hasFlash('success')){
		echo '<script>';
			echo '$(function() {'.
					'$(".flash-success").slideDown("fast");'.
					'setTimeout(function() {'.
						'$(".flash-success").slideUp("fast");'.
	    			'}, 4500);'.
				'});';
		echo '</script>';
	    echo '<div class="flash-success" style="display:none">';
			echo Yii::app()->user->getFlash('success');
	    echo '</div>';
	}	
	if(Yii::app()->user->hasFlash('error')){
		echo '<div class="flash-error">';
			echo Yii::app()->user->getFlash('error');
		echo '</div>';
	}
} ?>
