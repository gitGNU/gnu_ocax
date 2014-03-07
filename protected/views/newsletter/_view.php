<?php
/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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
 
/* @var $this NewsletterController */
/* @var $data Newsletter */
?>


<p style="margin-bottom:0px">
<?php echo __('Published on the').' '.format_date($data->created);?>
</p>
<div class="email" style="margin-top:0px">
	<div class="title">
	<span class="sub_title"><?php echo CHtml::encode($data->subject);?></span>
	</div>

<div style="font-size:1em"><?php echo $data->body; ?></div>

</div>
