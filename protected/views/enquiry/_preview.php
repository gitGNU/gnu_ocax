<?php

/**
 * OCAX -- Citizen driven MObservatory software
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

<style>
.enquiryPreview { margin-top:10px; border: solid 1px red; padding:2px; }
.enquiryPreview:hover { background-color:white; cursor: pointer; }

.enquiryPreview > .title { border-bottom: solid 1px red; font-size: 1.1em }
</style>


<div class="enquiryPreview" onclick="js:showEnquiry(<?php echo $data->id;?>); return false;">

<div class="title">
<?php echo $data->created.': '.$data->title; ?>
</div>

<div style="height:150px;overflow:hidden">
<?php echo $data->body; ?>
</div>

</div>
