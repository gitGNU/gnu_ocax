<?php
/* @var $this CommentController */
/* @var $data Comment */
?>

<div class="view" id="comment_<?php echo $data->id;?>">
	<p style="margin-bottom:5px">
	<b>
	<?php
		echo CHtml::encode($data->user0->fullname); ?></b> comenta el día <?php echo date( "Y-m-d H:m", strtotime($data->created));
		if($data->user == Yii::app()->user->getUserID())
			echo '<img style="cursor:pointer;margin-left:5px;" alt="Borrar" src="'.Yii::app()->theme->baseUrl.'/images/delete.png" onClick="js:deleteComment('.$data->id.')" />';
	?>
	</p>
	<p style="text-align:left">
	<?php echo $data->body; ?>
	</p>
</div>
