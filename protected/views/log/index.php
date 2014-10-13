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

/* @var $this LogController */
/* @var $model Log */


Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<script>
function toggleSearchOptions(){
	if ($("#searchOptions").is(":visible")){
		$("#searchOptionsToggle").html("<i class='icon-search-circled'></i>");
		$("#searchOptions").slideUp();
	}else{
		$("#searchOptionsToggle").html("<i class='icon-cancel-circled'></i>");
		$("#searchOptions").slideDown();
	}
}
</script>
<div style="position:relative;">
	<div id="searchOptionsToggle" class="color" onCLick="js:toggleSearchOptions();return false;">
		<i class="icon-search-circled"></i>
	</div>
</div>

<h1><?php echo __('Manage Logs');?></h1>

<div id="searchOptions" class="search-form">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view pgrid-cursor-pointer'),
	'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
	'loadingCssClass'=>'pgrid-view-loading',
	'id'=>'log-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'created',
			'type'=>'raw',
            'value'=>function($data,$row){
				return date("Y-m-d H:i:s", $data->created);
			},
		),
		'prefix',
		'message',
	),
)); ?>
