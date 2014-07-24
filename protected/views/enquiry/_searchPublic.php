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
?>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'search_enquiries',
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	));
	?>

	<?php
	echo '<p>';
	echo '<span>'.__('Text').'</span><br />';
	echo $form->textField($model,'body',array('width'=>'180px','maxlength'=>255)).' '.CHtml::submitButton(__('Search'));
	echo '</p>';

	// search options
	?>
	<p>
	<?php echo $form->label($model,'addressed_to'); ?><br />
	<?php echo $form->dropDownList($model, 'addressed_to', array(""=>__('Not filtered')) + $model->getHumanAddressedTo());?>
	</p>
	<p>
		<?php echo $form->label($model,'type'); ?><br />
		<?php echo $form->dropDownList($model, 'type', array(""=>__('Not filtered')) + $model->getHumanTypes());?>
	</p>
	<p>
	<?php echo $form->label($model,'state'); ?><br />
	<?php echo $form->dropDownList($model, 'state', array(""=>__('Not filtered')) + $model->getHumanStates());?>
	</p>

<?php $this->endWidget(); ?>
