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

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'search_enquiries',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<div style="margin-left:-10px" class="row">
		<?php
			echo $form->textField($model,'body',array('size'=>30,'maxlength'=>255));
			echo ' '.CHtml::submitButton(__('Search')).'<br />';
			echo '<p style="margin-top:15px; margin-bottom:-10px">';
			echo '<span style="float:left">'.__('Enquiries addressed to').'</span>';
			echo '<span style="float:left">';
			echo $form->radioButtonList($model,'addressed_to',
										$model->getHumanAddressedTo(),
										array(	'labelOptions'=>array('style'=>'display:inline'),
												'separator'=>'<br />',
												//'onchange'=>'js:$("#search_enquiries").submit();return false;',
												'onchange'=>'js:toggleAddressedTo(this); return false;',
											)
									);
			echo '</span></p><div style="clear:both;margin-bottom:-5px;"></div>';
		?>
	</div>

<?php $this->endWidget(); ?>
