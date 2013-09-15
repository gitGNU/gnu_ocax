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

/* @var $this EmailController */
/* @var $data Email */
?>

<div class="view">
	<div style="padding:10px;margin:-10px;background-color:#CAE1FF;margin-bottom:10px;font-size:1.3em;">
		<b>Subject</b>
		<?php echo CHtml::encode($data->title); ?>
	</div>

	<div style="padding:10px;margin:-10px;background-color:#F0F8FF;margin-bottom:15px;">
	<p style="margin-bottom:10px">
	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<?php
		if($data->sent)
			echo '<span style="color:green">Sent OK</span>';
		else
			echo '<span style="color:red">Failed</span>';
	?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sender')); ?>:</b>
	<?php
		if($data->sender)
			echo CHtml::encode($data->sender0->fullname);
		else
			echo 'Automatic email';
	?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sent_as')); ?>:</b>
	<?php echo CHtml::encode($data->sent_as); ?>
	</p>

	<b><?php echo CHtml::encode($data->getAttributeLabel('recipients')); ?>:</b>
	<?php echo CHtml::encode($data->recipients); ?>

	</div>

	<?php echo $data->body; ?>
	<br />

</div>
