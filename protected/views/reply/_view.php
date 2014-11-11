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

/* @var $this ReplyController */
/* @var $model Reply */
?>

<style>

</style>

<div class="reply">
<?php
	$user_id=Yii::app()->user->getUserID();
	echo '<div class="title">';
		echo '<span class="sub_title">'.__('Reply').': '.format_date($model->created).'</span>';

		echo '<div class="voteBlock">';
			$attachments = File::model()->findAllByAttributes(array('model'=>'Reply','model_id'=>$model->id));
			foreach($attachments as $attachment){
				echo '<span id="attachment_'.$attachment->id.'" style="margin-left:30px">';
				echo	'<span class="ocaxButton" style="padding:5px 8px 5px 5px;" onClick="js:viewFile(\''.$attachment->getWebPath().'\');">'.
						'<i class="icon-attach"></i>'.$attachment->name.'</span>';
				if( $model->team_member == $user_id ){
					echo '<i class="icon-cancel-circle red" style="cursor:pointer;margin-right:-10px;" onclick="js:deleteFile('.$attachment->id.');"></i>';
				}
				echo '</span>';
			}
			echo '<span style="margin-left:30px"></span>';
			echo '<span class="ocaxVote" onClick="js:vote('.$model->id.', 1);">'.
				 __('Vote').'<i class="icon-thumbs-up"></i>';
			echo '<span class="ocaxVoteCount" id="voteLikeTotal_'.$model->id.'">'.Vote::model()->getTotal($model->id, 1);
			echo '</span></span>';
			echo '<span style="margin-left:30px"></span>';
			echo '<span class="ocaxVote" onClick="js:vote('.$model->id.', 0);">'.
				 __('Vote').'<i class="icon-thumbs-down"></i>';
			echo '<span class="ocaxVoteCount" id="voteDislikeTotal_'.$model->id.'">'.Vote::model()->getTotal($model->id, 0);
			echo '</span></span>';

		echo '</div><div class="clear"></div>';
	echo '</div>';
	if($model->team_member == Yii::app()->user->getUserID()){
		echo '<div class="link" style="margin-top:-10px;float:right;" onClick=\'js:uploadFile("Reply",'.$model->id.');\'>'.__('Add attachment').'</div>';
		echo '<div class="clear"></div>';
	}
	echo '<div class="clear"></div>';
	// reply body
	echo '<p style="padding-top:30px;">'.$model->body.'</p>';

	$this->renderPartial('//comment/_showThread', array('model'=>$model));
?>
</div>
