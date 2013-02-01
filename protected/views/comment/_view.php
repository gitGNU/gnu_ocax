<?php
/* @var $this CommentController */
/* @var $data Comment */
?>

<div class="view">
	<p style="margin-bottom:5px">
	<b><?php echo CHtml::encode($data->user0->fullname); ?></b> comenta el día <?php echo CHtml::encode($data->created); ?>
	</p>
	<p>
	<?php echo $data->body; ?>
	</p>
</div>
