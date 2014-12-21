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

if($displayType == 'grid'){
	Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$.fn.yiiGridView.update('enquiry-grid', {
			data: $(this).serialize()
		});
		resetFormElements=1;
		return false;
	});
	");
}else{
	Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$.fn.yiiListView.update('enquiry-list', {
			data: $(this).serialize()
		});
		resetFormElements=1;
		return false;
	});
	");
}
$this->widget('EnquiryModal');
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/enquiry.css" />

<script>
var resetFormElements = 0;

$(function() {
	$(".workflowFilter").on('click', function() {
			filterByDiagram($(this).attr('state'));
	});
	$( document ).on( "mouseenter", ".enquiryPreview", function() {
		$(this).find('.title').addClass('highlightWithColor');
		$(this).find('.created').addClass('highlightWithColor');
	});
	$( document ).on( "mouseleave", ".enquiryPreview", function() {
		$(this).find('.title').removeClass('highlightWithColor');
		$(this).find('.created').removeClass('highlightWithColor');
	});
	$(window).scroll(function() {
		if($(this).scrollTop() > 300)
			$('.goToTop').fadeIn(500);
		else
			$('.goToTop').fadeOut(500);
	});
	$(".goToTop").click(function(){
		$("html, body").animate({ scrollTop: 0 }, 0);
		$('.goToTop').hide();
	});
});
function basicFilter(el, filter){
	$(el).parent().find('li').removeClass('activeItem');
	$(el).addClass('activeItem');
	$("#Enquiry_basicFilter").val(filter);
	$("#search_enquiries").submit();
}
function filterByDiagram(state){
	humanStates = <?php echo json_encode($model->getHumanStates()) ?>;
	$("#Enquiry_state").val(state);
	$("#search_enquiries").submit();
	
	//$("#humanStateTitle").html("<?php echo __('Filtered by:').' ';?>"+humanStates[state]);
}
function resetToggleIcons(){
	$('#searchOptionsToggle').find('i').removeClass('icon-cancel-circled');
	$('#workflowOptionsToggle').find('i').removeClass('icon-cancel-circled');
	$('#searchOptionsToggle').find('i').addClass('icon-search-circled');
	$('#workflowOptionsToggle').find('i').addClass('icon-flow-tree');
}
function toggleSearchOptions(){
	resetForm();
	resetToggleIcons();
	if ($("#advancedFilterOptions").is(":visible")){
		$("#advancedFilterOptions").hide();
		$("#workflowFilterOptions").show();
		$("#workflowFilterOptions").find('li').removeClass('activeItem');
		
	}else{
		$("#Enquiry_basicFilter").val('');
		$("#advancedFilterOptions").show();
		//$("#basicFilterOptions").hide();
		$("#workflowFilterOptions").hide();
		$('#searchOptionsToggle').find('i').removeClass('icon-search-circled');
		$('#searchOptionsToggle').find('i').addClass('icon-cancel-circled');
	}
}
function resetForm(){
	if(resetFormElements == 0)
		return;
	$('#Enquiry_searchText').val('');
	$("#Enquiry_state").val('');
	$('#Enquiry_type').val('');
	$('#Enquiry_searchDate_min').val('');
	$('#Enquiry_searchDate_max').val('');
	$("#Enquiry_basicFilter").val('');
	$("#search_enquiries").submit();
	resetFormElements = 0;
}
</script>

<div id="toggleIcons" style="position:relative;">
	<div id="searchOptionsToggle" onCLick="js:toggleSearchOptions();return false;">
		<i class="icon-search-circled"></i>
	</div>
</div>

<div id="enquiryPageTitle">
	<h1><?php echo __('Enquiries made to date');?></h1>
	<p style="margin-top:-15px;margin-bottom:0px;">
		<?php echo __('This is a list of enquiries made by citizens like you.');?>
	</p>
</div>
<div class="clear"></div>

<div id="filterOptions" style="margin-top:25px; margin-bottom: 5px; height:110px;"> <!-- filter options start -->

<div id="basicFilterOptions" class="tabMenu" style="height:95px; display:none;">
<?php
	echo '<div style="font-size:16px; height:50px; margin-bottom: 15px;">';
		echo __('Haz una consulta y participa.').'<br />';
		//echo __('Más consultas significa más cooperación entre ciudadanos').'.<br />';
		echo __('Aqui en el Observatorio nos encargamos de todo el papelaeo');
	echo '</div>';
?>
<ul>
<li onClick="js:basicFilter(this, 'noreply')"><?php echo __('Waiting for reply');?></li>
<li onClick="js:basicFilter(this, 'pending')"><?php echo __('Replies not yet assessed');?></li>
<li onClick="js:basicFilter(this, 'assessed')"><?php echo __('Assessed replies');?></li>
</ul>

</div>
<div id="advancedFilterOptions" style="height:95px;">
<div>
	<?php /* if(count($model->publicSearch()->getData()) > 0 ){ */ ?>
		<div class="search-form">
			<?php $this->renderPartial('_searchPublic',array(
				'model'=>$model,
			)); ?>
		</div><!-- search-form -->
	<?php /* } */ ?>
</div>
</div>

<div id="workflowFilterOptions" style="margin-top:-20px;height:95px; display:inline-block">
	<span style="font-size:16px">width:930px; height:100px</span><br />
	<img style="width:930px;height:100px" src="<?php echo Yii::app()->request->baseUrl;?>/images/horizontal-workflow.png" />
</div>

</div>	<!-- filter options end -->

<div id="enquiryDisplayTypeIcons">
<i class="icon-th-large" onclick="js:location.href='<?php echo Yii::app()->request->baseUrl;?>/enquiry?display=list'"></i>
<i class="icon-th-list" onclick="js:location.href='<?php echo Yii::app()->request->baseUrl;?>/enquiry?display=grid'"></i>
</div>

<div id="enquiryList" style="position:relative">
<span id="humanStateTitle"></span>
<?php

$template = '<div style="height:20px;">'.
			'<div style="float:left; position:absolute; top: -20px; left: 60px;">{summary}</div>'.
			'<div style="float:right; position:absolute; top: -20px; right:0px; ">{pager}</div><div class="clear">'.
			'</div></div>'.
			'{items}';

if($displayType == 'grid'){
	$this->widget('PGridView', array(
		'id'=>'enquiry-grid',
		'dataProvider'=>$model->publicSearch(),
		'rowCssClassExpression'=>function($row, $data){
			if(Yii::app()->user->isGuest)
				return $row % 2 ? 'even' : 'odd';
			if(EnquirySubscribe::model()->isUserSubscribed($data->id, Yii::app()->user->getUserID()))
				return 'tag_enquiry_row_as_subscribed';
			else
				return $row % 2 ? 'even' : 'odd';
		},
		'onClick'=>array(
			'type'=>'javascript',
			'call'=>'showEnquiry',
		),
		'ajaxUpdate'=>true,
		'pager'=>array(
			'class'=>'CLinkPager',
			'header'=>'',
			'maxButtonCount'=>6,
			'firstPageLabel'=> '<<',
			'prevPageLabel' => '<',
			'nextPageLabel' => '>',
			'lastPageLabel' => '>>',
		),
		'template' => $template,
		'columns'=>array(
		array(
			'header'=>__('Enquiries'),
			'name'=>'title',
			'value'=>'$data[\'title\']',
		),
		array(
			'header'=>__('Formulated'),
			'name'=>'created',
			'value'=>'format_date($data[\'created\'])',
		),
		array(
			'header'=>__('State'),
			'name'=>'state',
			'type' => 'raw',
			'value'=>'$data->getHumanStates($data[\'state\'])'
		),
	array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
	)));
}else{
	$this->widget('zii.widgets.CListView', array(
		'id'=>'enquiry-list',
		'dataProvider'=>$dataProvider,
		'afterAjaxUpdate'=>'function(){
							$("html, body").animate({scrollTop: $("#scrollTop").position().top }, 100);
							}',
		'itemView'=>'_preview',
		'emptyText'=>'<div class="sub_title" style="margin-bottom:60px;">'.__('No enquiries here').'</div>',
		'pager'=>array(
			'class'=>'CLinkPager',
			'header'=>'',
			'maxButtonCount'=>6,
			'firstPageLabel'=> '<<',
			'prevPageLabel' => '<',
			'nextPageLabel' => '>',
			'lastPageLabel' => '>>',
		),
		'template' => $template,

	));
}
?>
</div>

<?php if(Yii::app()->user->isGuest){
	echo '<div class="clear"></div>';
	echo '<div>';
		echo '<p>'.__('Haz una consulta y aporta tu granito de arena.').' ';
		echo __('Más consultas significa más cooperación entre ciudadanos').'. ';
		echo __('Aqui en el Observatorio nos encargamos de todo el papelaeo').'. ';
			echo __('Remember you must first').' '.
				'<a href="'.Yii::app()->request->baseUrl.'/site/login">'.__('login').'</a>'.' '.__('or').' '.
				'<a href="'.Yii::app()->request->baseUrl.'/site/register">'.__('create an account').'</a>'.
				'</p>';
	echo '</div>';
} ?>
<div class="clear"></div>
<div class="goToTop">&#x25B2;&nbsp;&nbsp;&nbsp;<?php echo __('go to top');?></div>
