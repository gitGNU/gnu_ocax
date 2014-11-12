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

$userCanCreate = Yii::app()->user->isPrivileged();
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/archive.css" />

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
<?php 
	echo $this->renderPartial('//file/modal');
} ?>


<div style="margin:0px 0 20px 0">
<?php
echo '<span class="bigTitle">'.__('Archive').'</span>';
if($userCanCreate){
	echo '<div style="float:right">';
	echo '<a class="link" href="'.getInlineHelpURL(':archive').'" target="_new">'.__('About the Archive').'</a><br />';
	echo '<span class="link" onClick="js:uploadFile()">'.__('Upload a file').'</span>';
	echo '</div>';
}
?>
</div>


<div style="margin-left:30px">
<?php
$user_id = Null;
$is_admin = Null;
if(!Yii::app()->user->isGuest){
	$user_id = Yii::app()->user->getUserID();
	$is_admin = Yii::app()->user->isAdmin();
}
$this->widget('zii.widgets.CListView', array(
	'id'=>'archive_list',
	//'template'=>'{items}<div style="clear:both"></div>{pager}',
	'template'=>'<p>{pager}</p>{items}<div style="clear:both"></div>',
	'dataProvider'=>$dataProvider,
	'viewData'=>array('user_id'=>$user_id,'is_admin'=>$is_admin),
	'itemView'=>'_view',
	'pager' => array(
			'class'			=> 'CLinkPager',
			'firstPageLabel'=> '<<',
			'prevPageLabel' => '<',
			'nextPageLabel' => '>',
			'lastPageLabel' => '>>',
		),
));
?>
<div style="clear:both"></div>
</div>


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
