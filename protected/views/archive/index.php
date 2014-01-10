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
</script>
<div id="files" class="modal" style="width:500px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="files_content" style="margin:-10px"></div>
</div>
<?php } ?>


<div>
<?php
echo '<h1 style="float:left">'.__('Archives').'</h1>';
if($userCanCreate){
	echo '<span class="link" style="float:right" onClick="js:uploadFile()">'.__('Upload a file').'</span>';
}
?>
</div>
<div style="clear:both"></div>


<style>
.archive {
	position: relative;
	text-decoration:none;
	border:1px solid red;
	min-width:250px;
	margin:15px;
	padding:3px 10px 0px 10px;
	float:left;
}
.archive .created {
	position: absolute;
	top: -10px;
	background-color: white;

}
.archive .name {
	font-size: 1.3em;	
}
.archive .description {
	background-color:white;
	padding: 5px;
	margin: 0 -10px 0 -10px;
}
.archive .delete {
	text-align:center;
	background-color:red;
	color:white;
	margin: 0 -10px 0 -10px;
}
</style>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'viewData'=>array('userCanDelete'=>$userCanCreate),
	'itemView'=>'_view',
)); ?>
<div style="clear:both"></div>
