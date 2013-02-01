<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

?>

<style>
.commentBlockLink { float:right;text-align:right; }
.commentBlockLink span { cursor:pointer; }
.clear { clear:both; }
</style>

<script>
function toggleComments(comments_block_id){
	if ($('#'+comments_block_id).is(":visible"))
		$('#'+comments_block_id).slideUp('fast');
	else
		$('#'+comments_block_id).slideDown('fast');
}
function getCommentForm(comment_on, id, el){
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
</script>


<div style="margin-top:15px">
<?php echo $model->body;?>

<?php
$commments = Comment::model()->findAll(array('condition'=>'consulta =  '.$model->id));

if($commments){
	echo '<div class="commentBlockLink">';
	echo '<span onClick="js:toggleComments(\'comments_consulta\')">Ver comentarios ('.count($commments).')</span>';
	echo '</div><br />';
}
elseif(!Yii::app()->user->isGuest){
	echo '<div class="commentBlockLink add_comment_link">';
	echo '<span onClick=\'js:getCommentForm("consulta",'.$model->id.',this)\'>Añadir comentario</span>';
	echo '</div>';
}

echo '<div id="comments_consulta" style="display:none">';
foreach($commments as $comment)
	$this->renderPartial('//comment/_view',array('data'=>$comment),false,false);

if(!Yii::app()->user->isGuest){
	echo '<div class="commentBlockLink add_comment_link">';
	echo '<span onClick=\'js:getCommentForm("consulta",'.$model->id.',this)\'>Añadir comentario</span>';
	echo '</div>';
}
echo '</div><div style="clear:both"></div>';
?>

<?php
$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));

foreach($respuestas as $respuesta){
	echo '<hr style="margin-bottom:0px">';
	echo '<p style="font-size:1.3em">Respuesta: '.date_format(date_create($respuesta->created), 'Y-m-d').'</p>';
	echo '<p>'.$respuesta->body.'</p>';
	$commments = Comment::model()->findAll(array('condition'=>'respuesta =  '.$respuesta->id));

	if($commments){
		echo '<div class="commentBlockLink">';
		echo '<span onClick="js:toggleComments(\'comments_respuesta_'.$respuesta->id.'\')">Ver comentarios ('.count($commments).')</span>';
		echo '</div><br />';
	}
	elseif(!Yii::app()->user->isGuest){
		echo '<div class="commentBlockLink add_comment_link">';
		echo '<span onClick=\'js:getCommentForm("respuesta",'.$respuesta->id.',this)\'>Añadir comentario</span>';
		echo '</div>';
	}

	echo '<div id="comments_respuesta_'.$respuesta->id.'" style="display:none">';
	foreach($commments as $comment)
		$this->renderPartial('//comment/_view',array('data'=>$comment),false,false);

	if(!Yii::app()->user->isGuest){
		echo '<div class="commentBlockLink add_comment_link">';
		echo '<span onClick=\'js:getCommentForm("respuesta",'.$respuesta->id.',this)\'>Añadir comentario</span>';
		echo '</div>';
	}
	echo '</div><div style="clear:both"></div>';
}?>
</div>

<div id="comment_form" style="display:none"></div>


