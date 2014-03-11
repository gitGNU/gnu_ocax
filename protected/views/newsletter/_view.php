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
<style>
#newsletterBody p { font-size:1em }
</style>

<span style="color:#8A8A8A;font-size:1.05em"><?php echo __('Published on the').' '.format_date($data->created);?></span>
<div class="horizontalRule"></div>
<h1 style="font-size:26pt;"><?php echo CHtml::encode($data->subject);?></h1>

<div id="newsletterBody" style="font-size:1.2em"><?php echo $data->body; ?></div>


