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
/* @var $model Enquiry */
/* @var $form CActiveForm */

$model = new Enquiry;
$stats = $model->getStatistics();
?>

<style>
#workflow_diagram > div.workflowFilter {
	position:absolute;
	/*height:48px;*/
	width:170px;
	border: solid 1px blue;
}
#workflow_diagram > div.workflowFilter > div.text{
	margin-left:40px;
	font-size:1em; line-height:100%;
	border: solid 1px red;
}
#workflow_diagram > div.workflowFilter:hover { background:white; color:black; cursor:pointer }

</style>

<div id="workflow_diagram" style="position:absolute; background-position: 0px 0px; width: 930px; height:120px; background-image: url('<?php echo Yii::app()->request->baseUrl; ?>/images/workflow-horizontal.png');">

<div class="workflowFilter" style="top:0px;left:0px;">
	<div class="text ">
		<?php echo $model->getHumanStates(ENQUIRY_PENDING_VALIDATION);?><br />
		<?php echo '<b>'.$stats['pending'].' '.__('enquiries').'</b>'; ?>
	</div>
</div>
<div class="workflowFilter" style="top:0px;left:190px;" state="<?php echo ENQUIRY_ACCEPTED;?>">
	<div class="text green" state="<?php echo ENQUIRY_ACCEPTED;?>">
		<?php echo $model->getHumanStates(ENQUIRY_ACCEPTED);?><br />
		<?php echo '<b>'.$stats['accepted'].' '.__('enquiries').'</b>'; ?>
	</div>
</div>
<div class="workflowFilter" style="top:60px;left:190px;" state="<?php echo ENQUIRY_REJECTED;?>">
	<div class="text red">
		<?php echo $model->getHumanStates(ENQUIRY_REJECTED);?><br />
		<?php echo '<b>'.$stats['rejected'].' '.__('enquiries').'</b>'; ?>
	</div>
</div>
<div class="workflowFilter" style="top:0px;left:380px;" state="<?php echo ENQUIRY_AWAITING_REPLY;?>">
	<div class="text">
		<?php echo $model->getHumanStates(ENQUIRY_AWAITING_REPLY);?><br />
		<?php echo '<b>'.$stats['waiting_reply'].' '.__('enquiries').'</b>'; ?>
	</div>
</div>
<div class="workflowFilter" style="top:0px;left:570px;" state="<?php echo ENQUIRY_REPLY_PENDING_ASSESSMENT;?>">
	<div class="text">
		<?php echo $model->getHumanStates(ENQUIRY_REPLY_PENDING_ASSESSMENT);?><br />
		<?php echo '<b>'.$stats['pending_assesment'].' '.__('enquiries').'</b>'; ?>
	</div>
</div>
<div class="workflowFilter" style="top:0px;left:760px" state="<?php echo ENQUIRY_REPLY_SATISFACTORY;?>">
	<div class="text green">
		<?php echo $model->getHumanStates(ENQUIRY_REPLY_SATISFACTORY);?><br />
		<?php echo '<b>'.$stats['reply_satisfactory'].'%</b>'; ?>
	</div>
</div>
<div class="workflowFilter" style="top:60px;left:760px" state="<?php echo ENQUIRY_REPLY_INSATISFACTORY;?>">
	<div class="text red">
		<?php echo $model->getHumanStates(ENQUIRY_REPLY_INSATISFACTORY);?><br />
		<?php echo '<b>'.$stats['reply_insatisfactory'].'%</b>'; ?>
	</div>
</div>

</div>


