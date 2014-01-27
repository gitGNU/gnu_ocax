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

/* @var $this UserController */
/* @var $model User */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo __('Users and roles');?></h1>

<?php echo CHtml::link(__('Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'username',
		//'fullname',
		'email',
		//'is_socio',
		'is_team_member',
		'is_editor',
		'is_manager',
		'is_admin',
		/*
		'joined',
		'activationcode',
		'activationstatus',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}',
			'buttons'=>array(
				'update' => array(
					'label'=>'Update',
		            'url'=>'Yii::app()->createUrl("user/updateRoles", array("id"=>$data->id))',
				),
			),
		),
	)
));?>






