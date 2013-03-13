<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

?>

<style>
.commentBlockLink { float:right;text-align:right; }
.commentBlockLink .link { color:#06c; cursor:pointer; }
.commentBlockLink .link:focus, .commentBlockLink .link:hover {color:#09f;}

.voteBlock { float:right;text-align:right; }
.voteBlock .like { padding:3px; background-color:#7CCD7C; }
.voteBlock .dislike { padding:3px; background-color:#FF6A6A;}

.votaLike { cursor:pointer; padding:3px; margin-right:5px; background-color:#C1FFC1; margin-right:10px; }
.votaDislike  { cursor:pointer; padding:3px; margin-right:5px; background-color:#FFAEB9; margin-right:0px; }
.voteTotal { font-weight: bold }

.clear { clear:both; }
</style>

<script>
function isUser(){
	if('1' == '<?php echo Yii::app()->user->isGuest;?>'){
		alert('Please login to participate');
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
		alert('Before participating, please confirm your email address');
	return <?php echo $participate?>;
}
function toggleComments(comments_block_id){
	//$('#comment_form').hide();
	if ($('#'+comments_block_id).is(":visible"))
		$('#'+comments_block_id).slideUp('fast');
	else
		$('#'+comments_block_id).slideDown('fast');
}
function getCommentForm(comment_on, id, el){
	if(!isUser())
		return;
	if(!canParticipate())
		return;
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/comment/getForm',
		type: 'GET',
		async: false,
		dataType: 'json',
		data: {'comment_on': comment_on, 'id': id },
		beforeSend: function(){ /*$ ('#right_loading_gif').show(); */ },
		complete: function(){ /* $('#right_loading_gif').hide(); */ },
		success: function(data){
			$('#comment_form').html();
			$('#comment_form').html(data.html);
			$(el).after($('#comment_form'));
			$('#comment_form').show();
		},
		error: function() {
			alert("Error on get comment form");
		}
	});
}
function cancelComment(){
	$('#comment_form').html();
	$('#comment_form').slideUp('fast');
}
function submitComment(form){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/comment/create',
		type: 'POST',
		async: false,
		dataType: 'json',
		data: $(form).serialize(),
		//beforeSend: function(){  },
		complete: function(){ $(form).parents('div:first').remove(); },
					
		success: function(data){
				if(data != 0){
					$(form).parents('.add_comment_link:first').before(data.html);
				}
		},
		error: function() { alert("error on create comment"); },
	});
}
function deleteComment(comment_id){
	retVal = confirm("¿Seguro que deseas borrarlo?");
	if( retVal == false ){
		return;
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/comment/delete/'+comment_id,
		type: 'POST',
		async: false,
		success: function(data){
				if(data == 1){
					$('#comment_'+comment_id).remove();
				}
		},
		error: function() { alert("error on delete comment"); },
	});
}
function vote(respuesta_id, like){
	if(!isUser())
		return;
	if(!canParticipate())
		return;
	if(like == 1)
		totalElement_id='voteLikeTotal_'+respuesta_id;
	else
		totalElement_id='voteDislikeTotal_'+respuesta_id;

	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/vote/create',
		type: 'POST',
		async: false,
		dataType: 'json',
		data: { 'respuesta': respuesta_id, 'like': like },
		//beforeSend: function(){ },
		//complete: function(){ },
		success: function(data){
				if(data != 0){
					if(data.already_voted){
						if(data.already_voted == 1)
							word='favorablemente';
						else
							word='desfavorablemente';
						alert('ya has votado '+word);
					}else
						$("#"+totalElement_id).html(data.total);
				}					
		},
		error: function() { alert("error on vote"); },
	});

}
</script>


<div style="margin-top:15px">
<?php echo $model->body;?>

<div class="commentBlockLink">

<?php
$commments = Comment::model()->findAll(array('condition'=>'consulta =  '.$model->id));
if($commments){
	echo '<div class="link" onClick="js:toggleComments(\'comments_consulta\')">Comentarios ('.count($commments).')</div>';

}
else{
	echo '<div class="add_comment_link">';
	echo '<span class="link" onClick=\'js:getCommentForm("consulta",'.$model->id.',this)\'>Añadir comentario</span>';
	echo '</div>';
}
echo '</div>';
echo '<div class="clear"></div>';

echo '<div id="comments_consulta" style="display:none">';
foreach($commments as $comment)
	$this->renderPartial('//comment/_view',array('data'=>$comment),false,false);


	echo '<div class="commentBlockLink add_comment_link">';
	echo '<span class="link" onClick=\'js:getCommentForm("consulta",'.$model->id.',this)\'>Añadir comentario</span>';
	echo '</div>';

echo '</div><div class="clear"></div>';
?>

<?php
$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));

foreach($respuestas as $respuesta){
	echo '<div class="respuesta">';	//open respuesta

	// title bar
	echo '<div class="title">';
	echo '<span style="font-size:1.4em;">Respuesta: '.date_format(date_create($respuesta->created), 'Y-m-d').'</span>';

	echo '<div class="voteBlock">';
	echo '<b>Valoraciones</b> ';
	echo '<span class="like">positivas <span id="voteLikeTotal_'.$respuesta->id.'" class="voteTotal">';
	echo Vote::model()->getTotal($respuesta->id, 1);
	echo '</span> </span>';
	echo '<span class="votaLike" onClick="js:vote('.$respuesta->id.', 1);">Vota</span>';
	echo '<span class="dislike">negativas <span id="voteDislikeTotal_'.$respuesta->id.'" class="voteTotal">';
	echo Vote::model()->getTotal($respuesta->id, 0);
	echo '</span> </span>';
	echo '<span class="votaDislike" onClick="js:vote('.$respuesta->id.', 0);">Vota</span>';
	echo '</div><div class="clear"></div>';
	echo '</div>';

	// attachments
	$attachments = File::model()->findAllByAttributes(array('model'=>'Respuesta','model_id'=>$respuesta->id));
	if($attachments || $model->team_member == Yii::app()->user->getUserID()){
		echo '<div class="attachments">';

		if($model->team_member == Yii::app()->user->getUserID()){
			echo '<span class="link" onClick="js:uploadFile('.$respuesta->id.');">Add attachment</span>';
			echo '<span style="float:right;text-align:right;width:80%;">';
			foreach($attachments as $attachment){
				if(!$attachment->name)
					$attachment->name=end(explode('/', $attachment->uri));
				echo '<span style="white-space: nowrap;margin-left:10px;">';
				echo '<a href="'.$attachment->webPath.'">'.$attachment->name.'</a> (delete) ';
				echo '</span>';
			}
			echo '</span>';
		}else{
			echo '<span style="float:right;text-align:right;">';
			echo '<img src="'.Yii::app()->theme->baseUrl.'/images/paper_clip.png" />Attachments:';
			foreach($attachments as $attachment){
				if(!$attachment->name)
					$attachment->name=end(explode('/', $attachment->uri));
				echo '<span style="white-space: nowrap;margin-left:10px;">';
				echo '<a href="'.$attachment->webPath.'">'.$attachment->name.'</a> ';
				echo '</span>';
			}
			echo '</span>';
		}
		echo '<div class="clear"></div></div>';
	}

	echo '<p>'.$respuesta->body.'</p>';
	$commments = Comment::model()->findAll(array('condition'=>'respuesta =  '.$respuesta->id));

	// comments
	echo '<div class="commentBlockLink">';
	if($commments){
		echo '<span class="link" onClick="js:toggleComments(\'comments_respuesta_'.$respuesta->id.'\')">Comentarios ('.count($commments).')</span>';
	}
	else{
		echo '<span class="add_comment_link">';
		echo '<span class="link" onClick=\'js:getCommentForm("respuesta",'.$respuesta->id.',this)\'>Añadir comentario</span>';
		echo '</span>';
	}
	echo '</div><div class="clear"></div>';

	echo '<div id="comments_respuesta_'.$respuesta->id.'" style="display:none">';
	foreach($commments as $comment)
		$this->renderPartial('//comment/_view',array('data'=>$comment),false,false);

		echo '<div class="commentBlockLink add_comment_link">';
		echo '<span class="link" onClick=\'js:getCommentForm("respuesta",'.$respuesta->id.',this)\'>Añadir comentario</span>';
		echo '</div>';

	echo '</div><div class="clear"></div>';

	echo '</div>';	//close respuesta
}?>
</div>

<div id="comment_form" style="display:none"></div>



<?php if ($model->team_member == Yii::app()->user->getUserID()) : ?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<style>           
	.bClose{
		cursor: pointer;
		position: absolute;
		right: -21px;
		top: -21px;
	}
</style>
<script>
function uploadFile(respuesta_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/create?model=Respuesta&model_id='+respuesta_id,
		type: 'POST',
		async: false,
		//dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
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
</script>
<div id="files" style="display:none;width:500px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="files_content" style="background-color:white;"></div>
</div>
<? endif ?>



