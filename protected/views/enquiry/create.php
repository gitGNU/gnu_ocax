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

?>

<?php
if($model->budget){
	echo '<span id="nueva_consulta" style="margin-top:-20px;cursor:auto;"></span>';
	echo '<h1>'.__('Formulate a').' '.__('budgetary enquiry').'</h1>';
}else{
	echo '<span id="nueva_consulta" style="margin-top:-10px;cursor:auto;"></span>';
	echo '<h1>'.__('Formulate a').' '.__('generic enquiry').'</h1>';
}
?>
</h1>
<?php
if(!$model->budget){
	echo '<div style="margin-top:-10px;margin-bottom:15px;">';
	echo __('If you wish to formulate a budgetary enquiry, you must first').' '.CHtml::link(__('find the corresponding budget'),array('/budget')).'</div>';

}
?>
<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 65%; float: left;  margin: 0px;}
	.right{width: 33%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="outer">
<div class="left">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>

<div class="right">
	<p style="font-size:1.5em"><?php echo __('Enquiry steps')?></p>
	<?php echo __('ENQUIRIES_STEP_MSG');?>
</div>
</div>
<div style="clear:both"></div>

<?php
if($model->related_to){
	echo '<div class="horizontalRule"></div>';
	echo '<div class="sub_title">'.__('The original enquiry').'</div';
	$related_enquiry=Enquiry::model()->findByPk($model->related_to);
	echo $this->renderPartial('_teamView', array('model'=>$related_enquiry));
}
?>
