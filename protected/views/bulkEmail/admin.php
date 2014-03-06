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

/* @var $this BulkEmailController */
/* @var $model BulkEmail */

$this->menu=array(
	array('label'=>__('Create bulk email'), 'url'=>array('create')),
);
$this->inlineHelp=':profiles:admin:newsletters';

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#bulk-email-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo __('Manage Bulk Emails');?></h1>

<?php echo CHtml::link(__('Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view pgrid-cursor-pointer'),
	'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
	'id'=>'bulk-email-grid',
	'selectableRows'=>1,
	'selectionChanged'=>'function(id){ location.href = "'.$this->createUrl('/bulkEmail/adminView').'/"+$.fn.yiiGridView.getSelection(id);}',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'created',
		'subject',
		array(
			'header'=>__('State'),
			'name'=>'sent',
			'type' => 'raw',
			'value'=>'$data->getHumanSentValues($data[\'sent\'])',
		),
		//'sent',

	),
)); ?>
