<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
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

/* @var $this EnquiryController */
/* @var $model Enquiry */

if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
}
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/fonts/fontello/css/fontello.css" />
<style>
i[class^="icon-"]:before, i[class*=" icon-"]:before {
	margin-top:0px;
	margin-right:3px;
	font-size:	17px;
}
</style>
<style>
.comments { margin-top:15px; }
.voteBlock { float:right;text-align:right; }
.clear { clear:both; }
</style>


<script>
function isUser(){
	if('1' == '<?php echo Yii::app()->user->isGuest;?>'){
		alert("<?php echo __('Please login to participate')?>");
		return 0;
	}else
		return 1;
}
function canParticipate(){
<?php
	if(!Yii::app()->user->isGuest && User::model()->findByAttributes(array('username'=>Yii::app()->user->id))->is_active)
		$participate = 1;
	else
		$participate = 0;
?>
	if('0' == '<?php echo $participate?>')
		alert("<?php echo __('Before participating, please confirm your email address')?>");
	return <?php echo $participate?>;
}
function toggleComments(comments_block_id){
	//$('#comment_form').hide();
	if ($('#'+comments_block_id).is(":visible"))
		$('#'+comments_block_id).slideUp('fast');
	else
		$('#'+comments_block_id).slideDown('fast');
}
function updateSubscriptionTotal(addMe){
	if($('#subscriptionTotal').length>0){
		total=parseInt($('#subscriptionTotal').html());
		$('#subscriptionTotal').html(total+addMe);
	}
}
function getCommentForm(comment_on, id, el){
	if(!isUser())
		return;
	if(!canParticipate())
		return;
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/comment/getForm',
		type: 'GET',
		dataType: 'json',
		data: {'comment_on': comment_on, 'id': id },
		beforeSend: function(){ /*$ ('#right_loading_gif').show(); */ },
		complete: function(){ /* $('#right_loading_gif').hide(); */ },
		success: function(data){
			//$('#comment_form').html();
			$('#comment_form').html(data.html);
			$(el).after($('#comment_form'));
			$('#comment_form').show();
			$('.add_comment_link').show();
			$('#comment_form').prev('.add_comment_link').hide();
		},
		error: function() {
			alert("Error on get comment form");
		}
	});
}
function cancelComment(){
	//$('#comment_form').html();
	$('#comment_form').slideUp('fast');
	$('#comment_form').prev('.add_comment_link').show();
}
function submitComment(form){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/comment/create',
		type: 'POST',
		dataType: 'json',
		data: $(form).serialize(),
		beforeSend: function(){
						$('#comment_form').find(':input').prop('disabled',true);
						$(form).find('.loading_gif').show();
					},
		complete: function(){
						$('#comment_form').html('');
						$('#comment_form').hide();			
					},
		success: function(data){
				if(data != 0){
					$('#comment_form').parents('.add_comment:first').before(data.html);
					
					show_comments_link = $('#comment_form').parents('.comments').find('.show_comments_link');
					comment_count = show_comments_link.find('.comment_count');
					count = parseInt(comment_count.html())+1;
					comment_count.html(count);
					show_comments_link.show();
					if($('#subscribe_checkbox').length>0)
						$('#subscribe_checkbox').attr('checked', true);
					updateSubscriptionTotal(data.newSubscription);
				}
				$('#comment_form').prev('.add_comment_link').show();
		},
		error: function() { alert("error on create comment"); },
	});
}
function deleteComment(comment_id){
	retVal = confirm("<?php echo __('Are you sure you want to delete it?');?>");
	if( retVal == false ){
		return;
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/comment/delete/'+comment_id,
		type: 'POST',
		success: function(data){
				if(data == 1){
					show_comments_link = $('#comment_'+comment_id).parents('.comments').find('.show_comments_link');
					comment_count = show_comments_link.find('.comment_count');
					count = comment_count.html() -1;
					comment_count.html(count);
					if(count == 0)
						show_comments_link.hide();
					$('#comment_'+comment_id).remove();
				}
		},
		error: function() { alert("error on delete comment"); },
	});
}
function vote(reply_id, like){
	if(!isUser())
		return;
	if(!canParticipate())
		return;
	if(like == 1)
		totalElement_id='voteLikeTotal_'+reply_id;
	else
		totalElement_id='voteDislikeTotal_'+reply_id;

	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/vote/create',
		type: 'POST',
		dataType: 'json',
		data: { 'reply': reply_id, 'like': like },
		success: function(data){
				if(data != 0){
					if(data.already_voted){
						if(data.already_voted == 1)
							alert('<?php echo __('You have already voted favourably');?>');
						else
							alert('<?php echo __('You have already voted unfavourably');?>');
					}else
						$("#"+totalElement_id).html(data.total);
				}					
		},
		error: function() { alert("error on vote"); },
	});
}
function getContactForm(recipient_id){
	if(!isUser())
		return;
	if(!canParticipate())
		return;
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/email/contactPetition',
		type: 'GET',
		data: {'recipient_id': recipient_id, 'enquiry_id': <?php echo $model->id?> },
		beforeSend: function(){ },
		complete: function(){ },
		success: function(data){
			if(data != 1){
				$('#contact_petition_content').html();
				$("#contact_petition_content").html(data);
				$('#contact_petition').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, speed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on get Contact petition");
		}
	});
}
function sendContactForm(form){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/email/contactPetition',
		type: 'POST',
		data: $('#'+form).serialize(),
		beforeSend: function(){
					$('#contact_petition_buttons').replaceWith($('#contact_petition_sending'));
					$('#contact_petition_sending').show();
					},
		complete: function(){ /* $('#right_loading_gif').hide(); */ },
		success: function(data){
			if(data == 1){
				$('#contact_petition_sending').replaceWith($('#contact_petition_sent'));
				$('#contact_petition_sent').show();

			}else{
				$('#contact_petition_sending').replaceWith($('#contact_petition_error'));
				$('#contact_petition_error').html(data);
				$('#contact_petition_error').show();
			}
			setTimeout(function() {
				$('#contact_petition').fadeOut('fast',
										function(){
											$('#contact_petition').bPopup().close();
									});
    		}, 2000);
		},
		error: function() {
			alert("Error on post Contact petition");
		}
	});
}
</script>

<div class="enquiryBody"><?php echo $model->body;?></div>

<div style="clear:both"></div>

<?php
echo '<div class="comments">';	// comments on enquiry open

$comments = Comment::model()->findAll(array('condition'=>'enquiry =  '.$model->id));
$visible='';
if(!$comments){
	$visible='style="display:none;"';
}

echo '<div class="show_comments_link link" '.$visible.' onClick="js:toggleComments(\'comments_enquiry\')">';
echo __('Comments').' (<span class="comment_count">'.count($comments).'</span>)</div>';

if(!$comments){
	echo '<div class="add_comment">';
	echo '<span class="link add_comment_link" onClick=\'js:getCommentForm("enquiry",'.$model->id.',this)\'>'.__('Add comment').'</span>';
	echo '</div>';	
}

echo '<div id="comments_enquiry" style="display:none">';
	foreach($comments as $comment){
		$this->renderPartial('//comment/_view',array('data'=>$comment),false,false);
	}
	if($comments){
		echo '<div class="add_comment">';
		echo '<span class="link add_comment_link" onClick=\'js:getCommentForm("enquiry",'.$model->id.',this)\'>'.__('Add comment').'</span>';
		echo '</div>';
	}

echo '</div>';
echo '</div>';	// comments on enquiry close

?>
<div class="clear"></div>

<?php
$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));

foreach($replys as $reply){
	echo '<div class="reply">';	//open reply

	// title bar
	echo '<div class="title">';
	echo '<span class="sub_title">'.__('Reply').': '.format_date($reply->created).'</span>';

	echo '<div class="voteBlock">';
	echo '<span style="margin-left:30px"></span>';
	
	echo '<span class="ocaxButton" style="padding:6px 8px 4px 12px;" onClick="js:vote('.$reply->id.', 1);">'.
		 __('Vote').'<i class="icon-thumbs-up"></i></span>';
	echo '<span class="ocaxButtonCount" style="padding:4px;" id="voteLikeTotal_'.$reply->id.'">'.Vote::model()->getTotal($reply->id, 1).'</span>';
	echo '<span style="margin-left:30px"></span>';
	echo '<span class="ocaxButton" style="padding:6px 8px 4px 12px;" onClick="js:vote('.$reply->id.', 0);">'.
		 __('Vote').'<i class="icon-thumbs-down"></i></span>';
	echo '<span class="ocaxButtonCount" style="padding:4px;" id="voteDislikeTotal_'.$reply->id.'">'.Vote::model()->getTotal($reply->id, 0).'</span>';	
	
	echo '</div><div class="clear"></div>';
	echo '</div>';

	// attachments
	$attachments = File::model()->findAllByAttributes(array('model'=>'Reply','model_id'=>$reply->id));
	if($attachments || $model->team_member == Yii::app()->user->getUserID()){
		echo '<div class="attachments">';

		if($model->team_member == Yii::app()->user->getUserID()){
			echo '<span class="link" onClick=\'js:uploadFile("Reply",'.$reply->id.');\'>'.__('Add attachment').'</span>';
			echo '<span style="float:right;text-align:right;">';
			foreach($attachments as $attachment){
				echo '<span style="white-space: nowrap;margin-left:10px;" id="attachment_'.$attachment->id.'">';
				echo '<a href="'.$attachment->getWebPath().'" target="_new">'.$attachment->name.'</a>';
				echo '	<img style="cursor:pointer;vertical-align:middle;"
						src="'.Yii::app()->request->baseUrl.'/images/delete.png" onclick="js:deleteFile('.$attachment->id.');" />';
				echo '</span>';
			}
			echo '</span>';
		}else{
			echo '<span style="float:right;text-align:right;white-space: nowrap;">';
			echo '<img style="vertical-align:text-top;" src="'.Yii::app()->request->baseUrl.'/images/paper_clip.png" />'.__('Attachments').': ';
			foreach($attachments as $attachment){
				//echo '<span style="white-space: nowrap;margin-left:10px;">';
				echo '<a href="'.$attachment->getWebPath().'" target="_new">'.$attachment->name.'</a> ';
				//echo '</span>';
			}
			echo '</span>';
		}
		echo '<div class="clear"></div></div>';
	}

	// reply body
	echo '<p style="padding-top:10px;">'.$reply->body.'</p>';
	
	echo '<div class="comments">';	//comments on reply open
	
	$comments = Comment::model()->findAll(array('condition'=>'reply =  '.$reply->id));
	$visible='';
	if(!$comments){
		$visible='style="display:none;"';
	}
	echo '<div class="show_comments_link link" '.$visible.' onClick="js:toggleComments(\'comments_reply_'.$reply->id.'\')">';
	echo __('Comments').' (<span class="comment_count">'.count($comments).'</span>)</div>';
	
	if(!$comments){
		echo '<div class="add_comment">';
		echo '<span class="link add_comment_link" onClick=\'js:getCommentForm("reply",'.$reply->id.',this)\'>'.__('Add comment').'</span>';
		echo '</div>';	
	}

	echo '<div id="comments_reply_'.$reply->id.'" style="display:none">';
		foreach($comments as $comment){
			$this->renderPartial('//comment/_view',array('data'=>$comment),false,false);
		}
		if($comments){
			echo '<div class="add_comment">';
			echo '<span class="link add_comment_link" onClick=\'js:getCommentForm("reply",'.$reply->id.',this)\'>'.__('Add comment').'</span>';
			echo '</div>';
		}
	echo '</div>';
	echo '</div>';		//comments on reply close
	
	echo '</div>';		//close reply
}?>
<div class="clear"></div>


<div id="comment_form" style="display:none"></div>

<div id="budget_popup" class="modal" style="width:900px;">
	<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
	<div id="budget_popup_body"></div>
</div>

<?php if (!Yii::app()->user->isGuest) : ?>
	<div id="contact_petition" class="modal" style="width:700px;">
		<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
		<div id="contact_petition_content" style="margin:-10px"></div>
	</div>
<? endif ?>


<?php if ($model->team_member == Yii::app()->user->getUserID()) : ?>
<script>
function uploadFile(model,model_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/create?model='+model+'&model_id='+model_id,
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
</script>
<div id="files_popup" class="modal" style="width:500px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="files_popup_content" style="margin:-10px;"></div>
</div>
<? endif ?>


