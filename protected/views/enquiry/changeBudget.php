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

$criteria = new CDbCriteria;
$criteria->condition = 'parent IS NULL AND year ='.$model->budget0->year;
$this_year=$budgetModel->find($criteria);

$this->menu=array(
	array('label'=>__('Edit year').' '.$this_year->year, 'url'=>array('/budget/updateYear/'.$this_year->id)),
	array('label'=>__('Manage years'), 'url'=>array('admin'))
);
$this->inlineHelp=':profiles:team_member';
$this->viewLog='Enquiry|'.$model->id;
?>

<h1><?php echo __('Edit enquiry').': '.__('change related budget')?></h1>
<p style="margin-top:-15px;margin-bottom:10px;">
<?php echo __('Use this when you need to make corrections to the budget database');?><i class="icon-attention green"></i>
</p>

<?php $this->renderPartial('_changeType',array('model'=>$model,'budgetModel'=>$budgetModel));?>
