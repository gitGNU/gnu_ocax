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
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('enquiry-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 63%; float: left;  margin: 0px;}
	.right{width: 33%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="outer">
<div class="left">

<h1>Enquiries encomendadas a mi</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

</div>
<div class="right">
	<b><?php echo __('States');?></b>
	<a href="http://ocax.net/?El_software:Workflow_y_estados" target="_new"><?php echo __('more info');?></a>	
	<br />
	<?php
		foreach($model->getHumanStates() as $key=>$value){
			echo $key.'&nbsp&nbsp'.$value.'<br />';
		}
	?>
</div>
</div>
<div class="clear" style="margin-bottom:15px"></div>

<?php
$this->widget('PGridView', array(
	'id'=>'enquiry-grid',
	'dataProvider'=>$model->teamMemberSearch(),
	//'filter'=>$model,
    'onClick'=>array(
        'type'=>'url',
        'call'=>'teamView',
    ),
	'ajaxUpdate'=>true,
	'columns'=>array(
	        array(
				'header'=>'Enquiry',
				'name'=>'title',
				'value'=>'$data->title',
			),
			'assigned',
			'state',
			/*
			'type',
			'capitulo',
			'title',
			'body',
			*/
            array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
?>


