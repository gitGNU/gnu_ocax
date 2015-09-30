<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2015 OCAX Contributors. See AUTHORS.

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

?>
<style>
.addressedToAlert{
	displat: none;
}
</style>

<script>
var alert_cnt = 0;
function changeAddressedTo(){
	if(alert_cnt > 2){
		$('#Enquiry_addressed_to_1').prop("checked",true);
		$('#Enquiry_addressed_to_0').prop("checked",false);
		$('#alert_popup').bPopup().close();
		alert_cnt = 0;
	}else{
		alert_cnt = alert_cnt + 1;
		$('#alert_cnt').html(alert_cnt+'/3');
		$('.addressedToAlert').hide();
		$('#alert_'+alert_cnt).show();
		$('#alert_popup').bPopup({
							modalClose: false
						, follow: ([false,false])
						, speed: 10
						, positionStyle: 'absolute'
						, modelColor: '#ae34d5'
					});
	}
}
function changeAddressedToCanceled(){
	$('#alert_popup').bPopup().close();
	$('#Enquiry_addressed_to_1').prop("checked",false);
	$('#Enquiry_addressed_to_0').prop("checked",true);
	alert_cnt = 0;	
}
function validate(){
	$('#Enquiry_state').val('<?php echo ENQUIRY_ACCEPTED;?>');
	$('#enquiry-form').submit();
}
function reject(){
	$('#Enquiry_state').val('<?php echo ENQUIRY_REJECTED;?>');
	$('#enquiry-form').submit();
}
$(function() {
	$("#Enquiry_addressed_to_1").on('click', function() {
		changeAddressedTo();
	});
})
</script>

<div id="alert_popup" class="modal" style="width:500px;">
	<div id="alert_popup_content">
	<div class="modalTitle"><?php echo __('Are you sure?');?>
	&nbsp;
	<span id ="alert_cnt"></span>
	</div>
	<p style="font-size:18px; padding-top:10px;">
		<?php echo '<span id="alert_1" class="addressedToAlert">'.__('What the Administration says matters, not the opinion of the Observatory!').'</span>';?>
		<?php echo '<span id="alert_2" class="addressedToAlert">'.__('The Observatory is not the local authority!').'</span>';?>
		<?php echo '<span id="alert_3" class="addressedToAlert">'.__('Citizens build a position of force when the Administration pronounces itself!').'</span>';?>
		<br /><br />
		<span	class="link" 
				onClick="<?php echo 'js:showHelp(\''.getInlineHelpURL(":manual:enquiry:who-replies").'\')';?>">
				<?php echo __('Read more about this decision');?>
		</span>
		<i class="icon-popup-1"></i>
	</p>
	<p>
		<?php echo __('Are you sure the observatory should respond to the enquiry?');?>
	</p>
	<input type="button" value="&nbsp;<?php echo __('Yes');?>&nbsp;" onClick="js:changeAddressedTo();return false;" />
	&nbsp;&nbsp;&nbsp;
	<input type="button" value="&nbsp;<?php echo __('No');?>&nbsp;" onClick="js:changeAddressedToCanceled();return false;" />
	</div>
</div>
