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

$related_enquiry=Enquiry::model()->findByPk($model->related_to);

$this->menu=array(
	array('label'=>__('View Enquiry'), 'url'=>array('teamView', 'id'=>$related_enquiry->id)),
	array('label'=>__('List enquiries'), 'url'=>array('managed')),
);

$this->inlineHelp=':profiles:team_member';
$this->viewLog='Enquiry|'.$related_enquiry->id;
?>

<?php
if(!$model->budget){
	echo '<div style="margin-top:-10px;margin-bottom:15px;">';
	echo __('If you wish to formulate a budgetary enquiry, you must first').' '.CHtml::link(__('find the corresponding budget'),array('/budget')).'</div>';

}
?>

<div style="padding:10px">
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>

<?php
	echo '<div class="horizontalRule" style="margin-top:30px"></div>';
	echo '<div class="sub_title">'.__('The original enquiry').'</div>';
	echo $this->renderPartial('_teamView', array('model'=>$related_enquiry));
?>
