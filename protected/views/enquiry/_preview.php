<?php

/**
 * OCAX -- Citizen driven Observatory software
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

/* @var $this EnquiryController */
/* @var $data Enquiry */

?>

<div id="preview_<?php echo $data->id;?>" class="enquiryPreview" onclick="js:showEnquiry(<?php echo $data->id;?>); return false;">

<span class="created"><?php echo format_date($data->created);?></span>
<span class="loading"></span>
<div class="title"><?php echo $data->title; ?></div>
<div class="body"><?php echo $data->body; ?></div>

</div>
