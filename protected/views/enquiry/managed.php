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
<script>
function toggleOptions(){
	resetForm();
	if ($("#search-form").is(":visible")){
		$("#search-form").hide();
		$("#search-form").show();
		$("#basicFilterOptions").find('li').removeClass('activeItem');
		$('#showSearchOptions').find('i').removeClass('icon-minus-circled');
		$('#showSearchOptions').find('i').addClass('icon-plus-circled');
	}else{
		$("#advancedFilterOptions").show();
		$("#search-form").hide();
		$('#showSearchOptions').find('i').removeClass('icon-plus-circled');
		$('#showSearchOptions').find('i').addClass('icon-minus-circled');
	}
}

</script>

	<div id="enquiryPageTitle">
<h1><?php echo __('Entrusted enquiries');?></h1>
<span><a href="http://wiki.ocax.net"><?php echo __('You are responsible for these enquiries');?></span></a>
	<div id="showSearchOptions" onCLick="js:toggleOptions();return false;">
		<?php echo __('Search options');?> <i class="icon-plus-circled"></i>
	</div>
</div>

<div class="search-form">
<?php $this->renderPartial('_memberSearch',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

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
				'header'=>__('Enquiry'),
				'name'=>'title',
				'value'=>'$data->title',
			),
			'assigned',
			array(
				'name'=>'state',
				'type'=>'raw',
    	        'value'=>function($data,$row){return $data->state.'.&nbsp;&nbsp'.Enquiry::getHumanStates($data->state);},
			),
            array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
?>
