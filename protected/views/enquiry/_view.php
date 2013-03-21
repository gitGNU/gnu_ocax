<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */
/* @var $form CActiveForm */

$user_id = Yii::app()->user->getUserID();
$user= User::model()->findByPk($user_id);
if(!$user->is_active)
	$this->renderPartial('//user/_notActiveInfo', array('model'=>$user));
?>

<script>
$(document).ready(function() {
	if(1 != <?php echo $user->is_active;?>){
		$('#enquiry-form').find(':input:not(:disabled)').prop('disabled',true);
		$('#enquiry-form').find(':textarea:not(:disabled)').prop('disabled',true);
	}
});
</script>


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title"><?php echo $model->isNewRecord ? __('New enquiry') : __('Modify enquiry')?></div>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'budget'); ?>


	<?php if($model->budget){
		echo '<div class="row" style="margin:-15px -10px 10px -10px;">';
		$this->renderPartial('//budget/_enquiryView',array('model'=>Budget::model()->findByPk($model->budget)));
		echo '</div>';
	}?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>

<?php
$this->widget('ext.tinymce.TinyMce', array(
    'model' => $model,
    'attribute' => 'body',
    // Optional config
    'compressorRoute' => 'tinyMce/compressor',
    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',

    'htmlOptions' => array(
        'rows' => 10,
        'cols' => 80,
    ),
));
?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? __('Publish') : __('Update')); ?>
		<?php	if (!$model->id)
					$cancelURL='/user/panel';
				elseif ($model->team_member == $user_id)	// remember: a team_memebr can edit a enquiry
					$cancelURL='/enquiry/teamView/'.$model->id;
				else
					$cancelURL='/enquiry/'.$model->id;
		?>
		<input type="button" value="<?php echo __('Cancel')?>" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     quiry_id': <?php echo $model->id?> },
		beforeSend: function(){ },
		complete: function(){ /* $('#right_loading_gif').hide(); */ },
		success: function(data){
			if(data != 1){
				$('#contact_petition_content').html();
				$("#contact_petition_content").html(data);
				$('#contact_petition').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
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
		async: false,
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

<?php echo $model->body;?>
<div style="clear:both"></div>

<?php
echo '<div class="commentBlockLink">';	// comments open

$commments = Comment::model()->findAll(array('condition'=>'enquiry =  '.$model->id));
if($commments){
	echo '<div class="link" onClick="js:toggleComments(\'comments_enquiry\')">'.__('Comments').' ('.count($commments).')</div>';
}
else{
	echo '<div class="add_comment_link">';
	echo '<span class="link" onClick=\'js:getCommentForm("enquiry",'.$model->id.',this)\'>'.__('Add comment').'</span>';
	echo '</div>';
}
echo '</div>';

echo '<div class="clear"></div>';

echo '<div id="comments_enquiry" style="display:none">';
foreach($commments as $comment)
	$this->renderPartial('//comment/_view',array('data'=>$comment),false,false);


	echo '<div class="commentBlockLink add_comment_link">';
	echo '<span class="link" onClick=\'js:getCommentForm("enquiry",'.$model->id.',this)\'>'.__('Add comment').'</span>';
	echo '</div>';
echo '</div>';

?>
<div class="clear"></div>

<?php
$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));

foreach($replys as $reply){
	echo '<div class="reply">';	//open reply

	// title bar
	echo '<div class="title">';
	echo '<span style="font-size:1.4em;">'.__('Reply').': '.date_format(date_create($reply->created), 'Y-m-d').'</span>';

	echo '<div class="voteBlock">';
	echo '<b>'.__('Valorations').'</b> ';
	echo '<span class="like">'.__('positive').' <span id="voteLikeTotal_'.$reply->id.'" class="voteTotal">';
	echo Vote::model()->getTotal($reply->id, 1);
	echo '</span> </span>';
	echo '<span class="votaLike" onClick="js:vote('.$reply->id.', 1);">'.__('Vote').'</span>';
	echo '<span class="dislike">'.__('negative').' <span id="voteDislikeTotal_'.$reply->id.'" class="voteTotal">';
	echo Vote::model()->getTotal($reply->id, 0);
	echo '</span> </span>';
	echo '<span class="votaDislike" onClick="js:vote('.$reply->id.', 0);">'.__('Vote').'</span>';
	echo '</div><div class="clear"></div>';
	echo '</div>';

	// reformulate and attachments
	$attachments = File::model()->findAllByAttributes(array('model'=>'Reply','model_id'=>$reply->id));
	if($attachments || $model->team_member == Yii::app()->user->getUserID()){
		echo '<div class="attachments">';

		if($model->team_member == Yii::app()->user->getUserID()){
			echo '<span class="link" onClick="js:uploadFile('.$reply->id.');">'.__('Add attachment').'</span>';
			echo '<span style="float:right;text-align:right;">';
			foreach($attachments as $attachment){
				echo '<span style="white-space: nowrap;margin-left:10px;" id="attachment_'.$attachment->id.'">';
				echo '<a href="'.$attachment->webPath.'" target="_new">'.$attachment->name.'</a>';
				echo '	<img style="cursor:pointer;vertical-align:text-top;"
						src="'.Yii::app()->theme->baseUrl.'/images/delete.png" onclick="js:deleteFile('.$attachment->id.');" />';
				echo '</span>';
			}
			echo '</span>';
		}else{
			echo '<span style="float:right;text-align:right;">';
			echo '<img style="vertical-align:text-top;" src="'.Yii::app()->theme->baseUrl.'/images/paper_clip.png" />'.__('Attachments').':';
			foreach($attachments as $attachment){
				echo '<span style="white-space: nowrap;margin-left:10px;">';
				echo '<a href="'.$attachment->webPath.'" target="_new">'.$attachment->name.'</a> ';
				echo '</span>';
			}
			echo '</span>';
		}
		echo '<div class="clear"></div></div>';
	}

	echo '<p>'.$reply->body.'</p>';
	$commments = Comment::model()->findAll(array('condition'=>'reply =  '.$reply->id));

	// comments
	echo '<div class="commentBlockLink">';
	if($commments){
		echo '<span class="link" onClick="js:toggleComments(\'comments_reply_'.$reply->id.'\')">'.__('Comments').' ('.count($commments).')</span>';
	}
	else{
		echo '<span class="add_comment_link">';
		echo '<span class="link" onClick=\'js:getCommentForm("reply",'.$reply->id.',this)\'>'.__('Add comment').'</span>';
		echo '</span>';
	}
	echo '</div><div class="clear"></div>';

	echo '<div id="comments_reply_'.$reply->id.'" style="display:none">';
	foreach($commments as $comment)
		$this->renderPartial('//comment/_view',array('data'=>$comment),false,false);

		echo '<div class="commentBlockLink add_comment_link">';
		echo '<span class="link" onClick=\'js:getCommentForm("reply",'.$reply->id.',this)\'>'.__('Add comment').'</span>';
		echo '</div>';

	echo '</div><div class="clear"></div>';
	echo '<div class="bottomBar">';
	echo __('Is this reply satisfactory? If not, you may').' '.CHtml::link(__('reformulate the enquiry'), array('enquiry/create', 'related'=>$model->id));
	echo '</div>';
	echo '</div>';	//close reply
}?>
</div>

<div id="comment_form" style="display:none"></div>

<?php if (!Yii::app()->user->isGuest) : ?>
<style>           
	.bClose{
		cursor: pointer;
		position: absolute;
		right: -21px;
		top: -21px;
	}
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>


<div id="contact_petition" style="display:none;width:700px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="contact_petition_content"></div>
</div>
<? endif ?>

<?php if ($model->team_member == Yii::app()->user->getUserID()) : ?>
<script>
function uploadFile(reply_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/create?model=Reply&model_id='+reply_id,
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
</script>
<div id="files" style="display:none;width:500px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="files_content" style="background-color:white;"></div>
</div>
<? endif ?>



