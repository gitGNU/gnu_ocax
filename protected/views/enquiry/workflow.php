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

/* @var $this EnquiryController */
/* @var $model Enquiry */
/* @var $form CActiveForm */
?>

<style>
.workflowText { position:absolute; font-size:0.95em; line-height:95%; width:150px;}
</style>
<div style="background-image: url('<?php echo Yii::app()->request->baseUrl; ?>/images/workflow.png'); height:415px; position:relative;">
<div class="workflowText" style="top:25px;left:120px;"><?php echo $model->getHumanStates(1);?></div>
<div class="workflowText" style="top:107px;left:0px;width:140px;"><?php echo $model->getHumanStates(4);?></div>
<div class="workflowText" style="top:107px;left:218px;width:145px;"><?php echo $model->getHumanStates(3);?></div>
<div class="workflowText" style="top:190px;left:120px;"><?php echo $model->getHumanStates(5);?></div>
<div class="workflowText" style="top:275px;left:120px;"><?php echo $model->getHumanStates(6);?></div>
<div class="workflowText" style="top:355px;left:0px;width:140px;"><?php echo $model->getHumanStates(8);?></div>
<div class="workflowText" style="top:355px;left:218px;width:145px;"><?php echo $model->getHumanStates(7);?></div>
</div>
