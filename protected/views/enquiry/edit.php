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

if(Yii::app()->user->getUserID() == $model->team_member){
	$this->menu=array(
		array('label'=>__('View Enquiry'), 'url'=>array('teamView', 'id'=>$model->id)),
		array('label'=>__('Change type'), 'url'=>array('changeType', 'id'=>$model->id)),
		array('label'=>__('List enquiries'), 'url'=>array('managed')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
	);
}
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));

if($replys || Yii::app()->user->getUserID() == $model->team_member)
	echo '<div class="view" style="padding:4px">';
?>

<?php if(Yii::app()->user->getUserID() == $model->team_member){
	$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>'Submitted por',
	        'value'=>$model->user0->fullname.' '.__('on the').' '.$model->created,
		),
		array(
	        'label'=>'Asignada a',
	        'value'=>($model->team_member) ? $model->teamMember->fullname.' '.__('on the').' '.$model->assigned : "",
		),
		array(
	        'label'=>__('Type'),
	        'value'=>$model->getHumanTypes($model->type),
		),
		'capitulo',
		array(
	        'label'=>__('State'),
	        'value'=>$model->getHumanStates($model->state),
		),
	),
	));
}?>

<?php

foreach($replys as $reply){
	echo '<hr>';
	echo '<p>';
	echo '<b>Reply: '.date_format(date_create($reply->created), 'Y-m-d').'</b><br />';
	echo '<p>'.$reply->body.'</p>';
	echo '</p>';
}

if($replys || Yii::app()->user->getUserID() == $model->team_member)
	echo '</div>';
?>


