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
#archiveOptions{
	float:right;
	padding-left:20px;
	padding-top:8px;
}
#archiveOptions i{
	cursor:pointer;
	font-size:26px;
}
.pgrid-view img {
	height: 28px;
	width: 28px;
}
</style>


<script>
$(function() {
	$(window).scroll(function() {
		if($(this).scrollTop() > 300)
			$('.goToTop').fadeIn(500);
		else
			$('.goToTop').fadeOut(500);
	});
	$(".goToTop").click(function(){
		$("html, body").animate({ scrollTop: 0 }, 0);
		$('.goToTop').hide();
	});
});
</script>

<?php if($userCanCreate){ ?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>

<script>
function uploadFile(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/archive/uploadFile',
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
function createContainer(){
	$.ajax({
		url: "<?php echo Yii::app()->request->baseUrl.'/archive/createContainer/'.$model->container; ?>",
		type: 'GET',
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

<?php $this->widget('InlineHelp'); ?>
<?php $this->widget('ViewLog'); ?>

<?php 
	echo $this->renderPartial('modal');
} ?>

<div style="margin:-15px 0 8px -15px;">
<?php
echo '<h1 style="float:left;"><i class="icon-folder-1"></i> '.__('Archive').'</h1>';
if($userCanCreate){
	echo '<div id="archiveOptions">';
	echo '<i title="'.__("Help").'" class="icon-help-circled" onCLick="js:showHelp(\''.getInlineHelpURL(":archive").'\');return false;"></i>';
	echo '<i title="'.__("Log").'" class="icon-book" onCLick="js:viewLog(\'Archive\');return false;"></i>';
	echo '<i title="'.__("Create a folder").'" class="icon-folder-1" onClick="js:createContainer();return false;"></i>';
	echo '<i title="'.__("Upload a file").'" class="icon-upload-cloud" onClick="js:uploadFile();return false;"></i>';
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
	'template' => '{pager} {items}',
	'ajaxUpdate'=>true,
	'columns'=>array(
		array(
			'type'=>'raw',
			'value'=>function($data){
				if ($data->is_container){
					$icon = '/images/fileicons/folder.svg';
				}else{
					$icon = '/images/fileicons/'.strtolower($data->extension).'.svg';
				}
				if (file_exists(dirname(Yii::app()->request->scriptFile).$icon)){
					return '<img class="icon" src="'.Yii::app()->baseUrl.$icon.'"/>';
				}
				return '<img class="icon" src="'.Yii::app()->baseUrl.'/images/fileicons/unknown.svg"/>';
			}
		),
		array(
			'name'=>__('Name'),
			'value'=>function($data){
				if ($data->extension){
					return $data->name.".".$data->extension;
				}
				return $data->name;
			}
		),
		array(
			'name'=>__('Description'),
			'value'=> '$data->description',
		),
		array(
			'name'=>__('Date'),
			'type'=>'raw',
			'value'=> '$data->getGridActions('.$user_id.', '.$is_admin.');',
		),	
	),
));
?>
<div style="clear:both"></div>
<div class="goToTop">&#x25B2;&nbsp;&nbsp;&nbsp;<?php echo __('go to top');?></div>

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
