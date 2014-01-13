<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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

$userCanCreate = Yii::app()->user->canCreateArchive();
?>

<?php if($userCanCreate){ ?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>

<script>
function uploadFile(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/archive/create',
		type: 'POST',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#files_content").html(data);
				$('#files').bPopup({
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
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			$.fn.yiiListView.update("archive_list",{});
		},
		error: function() {
			alert("Error on get archive/delete");
		}
	});
}
</script>
<div id="files" class="modal" style="width:500px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="files_content" style="margin:-10px"></div>
</div>
<?php } ?>


<div style="margin-top:-20px;">
<?php
echo '<span class="bigTitle">'.__('Archive').'</span>';
if($userCanCreate){
	echo '<span class="link" style="float:right" onClick="js:uploadFile()">'.__('Upload a file').'</span>';
}
?>
</div>
<div class="horizontalRule" style="clear:both;margin-bottom:20px;"></div>



<style>
.archive {
	position: relative;
	border:1px solid rgb(228, 222, 215);
	background-color: rgb(228, 222, 215);
	min-width:250px;
	margin: 15px 15px 45px 15px;
	float:left;
}
.archive a {
	color:grey;
	text-decoration:none;
}
.archive .created {
	position: absolute;
	border:1px solid rgb(228, 222, 215);
	padding: 0 5px 0 5px;
	height: 18px;
	width: 62px;
	top: -20px;
	left: -1px;
	color:grey;
	background-color: rgb(228, 222, 215);
}
.archive .delete {
	position: absolute;
	padding: 0 5px 0 5px;
	height: 20px;
	top: -20px;
	left: 80px;
	cursor: pointer;
	background-color:#DC143C;
	color:white;
}
.archive .name {
	padding: 3px 3px 0 3px;
	font-size: 1.2em;	
}
.archive .mime {
	float:right;
	margin: 5px 0 0 0;
}

.archive .description {
	max-width: 250px;
	background-color: white;
	margin-top: 3px;
	padding: 5px;
}
</style>

<div style="margin-left:30px">
<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'archive_list',
	'template'=>'{items}<div style="clear:both"></div>{pager}',
	'dataProvider'=>$dataProvider,
	'viewData'=>array('userCanDelete'=>$userCanCreate),
	'itemView'=>'_view',
)); ?>
<div style="clear:both"></div>
</div>


<?php if($userCanCreate){
	if(Yii::app()->user->hasFlash('error')){
		echo '<div class="flash-error">';
			echo Yii::app()->user->getFlash('error');
		echo '</div>';
	}
} ?>
