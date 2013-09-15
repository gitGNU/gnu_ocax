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

<h1><?php echo __('Formulate a').' '?>
<?php
if($model->budget)
	echo __('budgetary enquiry');
else
	echo __('generic enquiry');
?>
</h1>
<?php
if(!$model->budget){
	echo '<div style="margin-top:-10px;margin-bottom:15px;">';
	echo __('If you wish to formulate a budgetary enquiry, you must first').' '.CHtml::link('buscar el concepto presupestario',array('/budget')).'</div>';

}
?>
<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 75%; float: left;  margin: 0px;}
	.right{width: 23%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="outer">
<div class="left">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>

<div class="right">
	<p style="font-size:1.5em">Procedimiento</p>
	<p>Tu creas la enquiry</p>
	<p>Nosotros la asignamos a una persona de nuestro equipo quien se encargará de ella.</p>
	<p>Recibirás correos informándote del proceso.</p>
	<p>más cosas</p>
	<p>más cosas</p>
</div>
</div>
<div style="clear:both"></div>

<?php
	if($model->related_to){
		$related_enquiry=Enquiry::model()->findByPk($model->related_to);
		$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$related_enquiry->id));

		echo '<div class="enquiry" style="margin-top:20px">';
		echo '<div class="title">'.__('The original enquiry').'</div>';

		echo '<div style="margin:-15px -10px 10px -10px;">';
		$this->widget('zii.widgets.CDetailView', array(
			'data'=>$related_enquiry,
			'attributes'=>array(
				'created',
				array(
			        'label'=>__('Type'),
			        'value'=>$related_enquiry->getHumanTypes($related_enquiry->type),
				),
				array(
			        'label'=>__('State'),
					'type' => 'raw',
			        'value'=>$related_enquiry->getHumanStates($related_enquiry->state),
				),
			),
		));
		if($related_enquiry->budget){
			$budget=Budget::model()->findByPk($related_enquiry->budget);
			echo $this->renderPartial('//budget/_enquiryView', array('model'=>$budget));
		}
		echo '</div>';

		$this->renderPartial('_view', array('model'=>$related_enquiry,'replys'=>$replys,));
		echo '</div>';
	}
?>

