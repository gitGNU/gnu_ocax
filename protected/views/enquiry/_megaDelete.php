<?php



?>
<div id="enquiry" style="display:none;width:850px;">
	<div style="background-color:white;padding:5px;">
		<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
		<div style="margin:-5px;background-color:orange">
			<h1 style="text-align:center;">!! Delete enquiry !!</h1>
		</div>
		<div id="enquiry_body" style="margin:-5px;margin-top:-15px;"></div>
	</div>

	<div style="background-color:orange;padding:5px;">
		<h1 style="text-align:center;">Are you sure you want to delete it all?</h1>
		<div style="width:100%">
			<div style="float:left;width:80%;color:black;font-weight:strong;">
				<ul>
				<li>Replies made by team members</li>
				<li>Files uploaded by team members</li>
				<li>Record of sent emails</li>
				<li>Comments</li>
				<li>Votes</li>
				<li>User email subscriptions</li>
				</ul>
			</div>
		</div>
		<div style="float:left;margin-top:35px;">
			<input type="button" id="mega_delete_button" enquiry_id="" onClick="js:megaDelete(this)" value="Yes, delete it all" />
		</div>
		<div style="clear:both"></div>
	</div>
</div>

