<?php

	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;


?>
<div style="margin:-5px;background-color:orange">
	<h1 style="text-align:center;">!! Delete enquiry !!</h1>
</div>

<div id="enquiry_body" style="margin:-5px;margin-top:-15px;">
	<?php echo $this->renderPartial('//enquiry/_teamView', array('model'=>$model)); ?>
</div>

<div style="background-color:orange;padding:5px;margin:-5px;">
	<h1 style="text-align:center;">Are you sure you want to delete it all?</h1>
	<div style="width:100%">
		<div style="float:left;width:80%;color:black;font-weight:strong;">
			<ul>
			<?php
			echo '<li>'.__('Reformulated enquires').' ('.$object_count['reforumulated'].')</li>';
			echo '<li>'.__('Replies').' ('.$object_count['replys'].')</li>';
			echo '<li>'.__('Files').' ('.$object_count['files'].')</li>';
			echo '<li>'.__('Record of sent emails').' ('.$object_count['emails'].')</li>';
			echo '<li>'.__('Comments').' ('.$object_count['comments'].')</li>';
			echo '<li>'.__('Votes').' ('.$object_count['votes'].')</li>';
			echo '<li>'.__('User email subscriptions').' ('.$object_count['subscriptions'].')</li>';
			?>
			</ul>
		</div>
	</div>
	<div style="float:left;margin-top:35px;">
		<input type="button" id="mega_delete_button" enquiry_id="" onClick="js:megaDelete(this)" value="Yes, delete it all" />
	</div>
	<div style="clear:both"></div>
</div>




