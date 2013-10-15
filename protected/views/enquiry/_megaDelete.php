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

Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

?>
<div style="margin:-10px;background-color:orange">
	<h1 style="text-align:center;color:black;padding:15px;">!! Delete enquiry !!</h1>
</div>

<div id="enquiry_body" style="margin:-5px;margin-top:-15px;">
	<?php echo $this->renderPartial('//enquiry/_teamView', array('model'=>$model)); ?>
</div>

<div style="background-color:orange;padding:5px;margin:-10px;margin-top:5px;margin-bottom:-10px;">
	<h1 style="text-align:center;color:black;padding:15px;">Are you sure you want to delete it all?</h1>
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





