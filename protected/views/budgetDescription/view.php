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

/* @var $this BudgetDescriptionController */
/* @var $model BudgetDescription */

$this->menu=array(
	array('label'=>__('Create description'), 'url'=>array('create')),
	array('label'=>__('Update description'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>__('Delete description'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>__('Manage descriptions'), 'url'=>array('admin')),
);
?>

<h1><?php echo __('Budget description');?></h1>
<p>

<?php $this->widget('zii.widgets.CDetailView', array(
	'cssFile' => Yii::app()->theme->baseUrl.'/css/pdetailview.css',
	'data'=>$model,
	'attributes'=>array(
		'csv_id',
		'language',
		'code',
		'label',
		'concept',
		'common',
	),
)); ?>

</p>

<?php
	if($model->text){
		echo '<h2>'.__('Description').'</h2>';
		echo $model->description;
	}else
		echo '<span style="font-size:1.3em;color:red;">'.__('Description not defined').'</span>';
?>
