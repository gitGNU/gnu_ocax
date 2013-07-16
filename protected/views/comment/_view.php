<?php
/* @var $this CommentController */
/* @var $data Comment */
?>

<div class="view" id="comment_<?php echo $data->id;?>">
	<p style="margin-bottom:5px"><b>
	<?php if(! ($data->user0->username == Yii::app()->user->id || $data->user0->is_disabled == 1))
			echo '<span class="link" onClick="js:getContactForm('.$data->user.')">';
		else
			echo '<span>';
	?>
	<?php echo CHtml::encode($data->user0->fullname); ?></span></b>

	<?php echo __('comments on the').' '.format_date($data->created,1);
		if($data->user == Yii::app()->user->getUserID())
			echo '<img style="cursor:pointer;margin-left:5px;vertical-align:middle" alt="'.__('Delete').'" src="'.Yii::app()->theme->baseUrl.'/images/delete.png" onClick="js:deleteComment('.$data->id.')" />';
	?>
	</p>
	<p style="text-align:left">
	<?php echo $data->body; ?>
	</p>
</div>
