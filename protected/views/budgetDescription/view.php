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
	//array('label'=>__('Create description'), 'url'=>array('create')),
	array('label'=>__('Update description'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>__('Delete description'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>__('Budgets without description'), 'url'=>array('budget/noDescriptions')),
	array('label'=>__('Manage descriptions'), 'url'=>array('admin')),
);
$this->inlineHelp=':budget_descriptions';
?>

<h1><?php echo __('Budget description');?></h1>
<p>

<?php
$languages = explode(',', Config::model()->findByPk('languages')->value);
$langStr='';
if(isset($languages[1])){
	$languages = array_reverse($languages);
	foreach($languages as $lang){
		$langStr = CHtml::link($lang, array('budgetDescription/translate?lang='.$lang.'&csv_id='.$model->csv_id)).' '.$langStr;
	}
}

$this->widget('zii.widgets.CDetailView', array(
	'cssFile' => Yii::app()->request->baseUrl.'/css/pdetailview.css',
	'data'=>$model,
	'attributes'=>array(
		'csv_id',
		'language',
		'code',
		'label',
		array(            
			'label'=>__('Used where'),
			'type'=>'raw',
			'value'=>$model->whereUsed(),
		),
		array(            
			'label'=>__('Translations'),
			'visible' => isset($languages[1]),
			'type'=>'raw',
			'value'=>$langStr,
		),
	),
)); ?>
</p>

<div class="horizontalRule"></div>

<?php
	echo '<h2 style="margin-bottom:-5px">'.__('Concept').'</h2>';
	echo '<p>'.$model->concept.'</p>';

	echo '<h2 style="margin-bottom:-5px">'.__('Description').'</h2>';
	if($model->text)		
		echo '<div style="font-size:1.2em">'.$model->description.'</div>';
	else
		echo '<p style="color:red;">'.__('Description not defined').'</p>';
?>
