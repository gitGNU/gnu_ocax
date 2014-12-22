<?php

/**
 * OCAX -- Citizen driven Observatory software
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

if(!isset($model))
	$model = new Enquiry;
?>

<style>
#workflow_diagram > div.text {
	position:absolute;
	height:55px;
	font-size:0.95em; line-height:95%; width:140px;
	border: solid 1px red;
}
#workflow_diagram > div.workflowFilter {
	position:absolute;
	height:55px;
	font-size:0.95em; line-height:95%; width:150px;
	border: solid 1px blue;
}
</style>

<div id="workflow_diagram" style="position:absolute; background-position: 0px 0px; width: 930px; height:120px; background-image: url('<?php echo Yii::app()->request->baseUrl; ?>/images/workflow-horizontal.png');">

<div class="text " style="top:0px;left:50px;">
	<?php echo $model->getHumanStates(ENQUIRY_PENDING_VALIDATION);?><br />
	<?php echo '<b>5 consultas</b>'; /*$stats['pending'].'%'*/?>
</div>
<div class="text" style="top:0px;left:245px;width:140px;" state="<?php echo ENQUIRY_ACCEPTED;?>">
	<?php echo $model->getHumanStates(ENQUIRY_ACCEPTED);?><br />
	<?php echo '<b>5 consultas</b>'; /*$stats['pending'].'%'*/?></div>
<div class="text" style="top:60px;left:255px;width:145px;" state="<?php echo ENQUIRY_REJECTED;?>">
	<?php echo $model->getHumanStates(ENQUIRY_REJECTED);?><br />
	<?php echo '<b>5 consultas</b>'; /*$stats['pending'].'%'*/?></div>
<div class="text" style="top:0px;left:445px;" state="<?php echo ENQUIRY_AWAITING_REPLY;?>">
	<?php echo $model->getHumanStates(ENQUIRY_AWAITING_REPLY);?><br />
	<?php echo '<b>5 consultas</b>'; /*$stats['pending'].'%'*/?></div>
<div class="text" style="top:0px;left:645px;" state="<?php echo ENQUIRY_REPLY_PENDING_ASSESSMENT;?>">
	<?php echo $model->getHumanStates(ENQUIRY_REPLY_PENDING_ASSESSMENT);?><br />
	<?php echo '<b>5 consultas</b>'; /*$stats['pending'].'%'*/?></div>
<div class="text" style="top:0px;left:820px;width:120px;" state="<?php echo ENQUIRY_REPLY_INSATISFACTORY;?>">
	<?php echo $model->getHumanStates(ENQUIRY_REPLY_INSATISFACTORY);?><br />
	<?php echo '<b>50%</b>'; /*$stats['pending'].'%'*/?></div>
<div class="text" style="top:60px;left:820px;width:120px;" state="<?php echo ENQUIRY_REPLY_SATISFACTORY;?>">
	<?php echo $model->getHumanStates(ENQUIRY_REPLY_SATISFACTORY);?><br />
	<?php echo '<b>50%</b>'; /*$stats['pending'].'%'*/?></div>



<div class="workflowFilter" style="top:0px;left:0px;width:190px;"></div>
<div class="workflowFilter" style="top:0px;left:200px;width:190px;" state="<?php echo ENQUIRY_ACCEPTED;?>"></div>
<div class="workflowFilter" style="top:60px;left:255px;width:190px;" state="<?php echo ENQUIRY_REJECTED;?>"></div>
<div class="workflowFilter" style="top:0px;left:400px;width:190px;" state="<?php echo ENQUIRY_AWAITING_REPLY;?>"></div>
<div class="workflowFilter" style="top:0px;left:600px;;width:190px;" state="<?php echo ENQUIRY_REPLY_PENDING_ASSESSMENT;?>"></div>
<div class="workflowFilter" style="top:0px;left:790px;width:140px;" state="<?php echo ENQUIRY_REPLY_INSATISFACTORY;?>"></div>
<div class="workflowFilter" style="top:60px;left:790px;width:150px;" state="<?php echo ENQUIRY_REPLY_SATISFACTORY;?>"></div>




</div>



<?php if(isset($showStats)){
	$stats = $model->getStatistics();
?>
<div style="top:53px;left:93px;width:30px;"><?php echo $stats['pending'].'%'?></div>
<div style="top:140px;left:145px;width:30px;"><?php echo $stats['accepted'].'%'?></div>
<div style="top:140px;left:190px;width:30px;"><?php echo $stats['rejected'].'%'?></div>
<div style="top:223px;left:93px;width:30px;"><?php echo $stats['waiting_reply'].'%'?></div>
<div style="top:302px;left:93px;width:30px;"><?php echo $stats['pending_assesment'].'%'?></div>
<div style="top:388px;left:145px;width:30px;"><?php echo $stats['reply_insatisfactory'].'%'?></div>
<div style="top:388px;left:190px;width:30px;"><?php echo $stats['reply_satisfactory'].'%'?></div>
<?php } ?>

</div>
