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

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('enquiry-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo __('Manage enquiries');?></h1>

<div class="search-form">
<?php $this->renderPartial('_managerSearch',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view pgrid-cursor-pointer'),
	'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
	'loadingCssClass'=>'pgrid-view-loading',
	'id'=>'enquiry-grid',
	'selectableRows'=>1,
	'selectionChanged'=>'function(id){ location.href = "'.$this->createUrl('enquiry/adminView').'/"+$.fn.yiiGridView.getSelection(id);}',
	'dataProvider'=>$model->adminSearch(),
	//'filter'=>$model,
	'columns'=>array(
		'title',
		'created',
		array(
			'header'=>__('Assigned to'),
			'name'=>'username',
			'value'=>'($data->teamMember) ? $data->teamMember->fullname : ""',
		),
		array(
			'name'=>'state',
			'type'=>'raw',
			//'value'=>'Enquiry::getHumanStates($data->state)',
            'value'=>function($data,$row){return $data->state.'.&nbsp;&nbsp'.Enquiry::getHumanStates($data->state);},
		),
	),
)); ?>

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash-success').slideUp('fast');
    	}, 3000);
		});
	</script>
    <div class="flash-success">
		<?php echo Yii::app()->user->getFlash('success');?>
    </div>
<?php endif; ?>
