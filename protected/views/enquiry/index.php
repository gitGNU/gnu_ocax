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
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/enquiry.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>

<style>
	 
</style>

<script>
var resetFormElements = 0;

$(function() {
	$(".workflowFilter").on('click', function() {
			filterByDiagram($(this).attr('state'));
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
function showEnquiry(enquiry_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/getEnquiry/'+enquiry_id,
		type: 'GET',
		dataType: 'json',
		beforeSend: function(){
						$('#preview_'+enquiry_id).find('.loading').show();
					},
		complete: function(){ 
						$('#preview_'+enquiry_id).find('.loading').hide();
						FB.XFBML.parse();
						twttr.widgets.load();
					},
		success: function(data){
			if(data != 0){
				$("#enquiry_body").html(data.html);
				$('#enquiry_popup').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
					, speed: 10
                });
			}
		},
		error: function() {
			alert("Error on show enquiry");
		}
	});
}
function toggleOptions(){
	resetForm();
	if ($("#advancedFilterOptions").is(":visible")){
		$("#advancedFilterOptions").hide();
		$("#basicFilterOptions").show();
		$("#basicFilterOptions").find('li').removeClass('activeItem');
		$('#filterLabel').html("<?php echo __('show options');?> &#x25BC;");
	}else{
		$("#Enquiry_basicFilter").val('');
		$("#advancedFilterOptions").show();
		$("#basicFilterOptions").hide();
		$('#filterLabel').html("<?php echo __('hide options');?> &#x25B2;");
	}
}
function resetForm(){
	if(resetFormElements == 0)
		return;
	$('#Enquiry_searchText').val('');
	$("#Enquiry_state").val('');
	$('#Enquiry_addressed_to').val('');
	$('#Enquiry_type').val('');
	$('#Enquiry_searchDate_min').val('');
	$('#Enquiry_searchDate_max').val('');
	$("#Enquiry_basicFilter").val('');
	$("#search_enquiries").submit();
	resetFormElements = 0;
}
</script>
<div id="enquiryPageTitle">
	<div style="float:left;">
		<h1><?php echo __('Enquiries made to date');?></h1>
		<p style="margin-top:-15px;margin-bottom:0px;">
			<?php echo __('This is a list of enquiries made by citizens like you.');?>
		</p>
	</div>
	<div style="float:right">
		<div id="filterLabel" class="moreOptionsArrow" style="font-size: 2em; cursor:pointer;" onCLick="js:toggleOptions();return false;">
			<?php echo __('show options');?> &#x25BC;
		</div>
		<div style="text-align:right">
			<div id="change_to_list" onClick="js:location.href='<?php echo Yii::app()->request->baseUrl;?>/enquiry?display=list'"></div>
			<div id="change_to_grid" onClick="js:location.href='<?php echo Yii::app()->request->baseUrl;?>/enquiry?display=grid'"></div>
		</div>
	</div>
</div>
<div class="clear"></div>


<div id="basicFilterOptions">
<ul>
<li onClick="js:basicFilter(this, 'noreply')"><?php echo __('Waiting for reply');?></li>
<li onClick="js:basicFilter(this, 'pending')"><?php echo __('Replies not yet assessed');?></li>
<li onClick="js:basicFilter(this, 'assessed')"><?php echo __('Assessed replies');?></li>
</ul>
<div class="clear"></div>
</div>

<div id="advancedFilterOptions">

<div style="float:left; width:360px; margin-right:120px;">
	<div id="workflow" style="padding-bottom:5px;">
		<!-- <p style="text-align:center;margin-top:30px;"> -->
		<?php /* echo __('What are the different states of an enquiry?'); */ ?>
		<!-- </p> -->
		<div><?php $this->renderPartial('workflow',array('model'=>$model,'showStats'=>1));?></div>
	</div>
</div>

<div style="float:left">
	<?php if(count($model->publicSearch()->getData()) > 0 ){ ?>
		<div class="search-form">
			<?php $this->renderPartial('_searchPublic',array(
				'model'=>$model,
			)); ?>
		</div><!-- search-form -->
	<?php } ?>
</div>

<div class="clear"></div>
<div class="horizontalRule"></div>
</div>	<!-- options end -->

<div id="enquiryList">
<span id="humanStateTitle"></span>
<?php
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
		//'emptyText'=>'<div id="noEnquiriesHere">'.__('No enquiries here').'.</div>',
		'onClick'=>array(
			'type'=>'javascript',
			'call'=>'showEnquiry',
		),
		'ajaxUpdate'=>true,
		'pager'=>array(
			'class'=>'CLinkPager',
			'header'=>'',
			'maxButtonCount'=>6,
			'prevPageLabel'=>'< Prev',
		),
		'template' => "{summary}{items}{pager}",
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
			'value'=>'$data->getHumanStates($data[\'state\'],$data[\'addressed_to\'])'
		),
	array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
	)));
}else{
	$this->widget('zii.widgets.CListView', array(
		'id'=>'enquiry-list',
		//'template'=>'{items}<div style="clear:both"></div>{pager}',
		'dataProvider'=>$dataProvider,
		'afterAjaxUpdate'=>'function(){
							$("html, body").animate({scrollTop: $("#scrollTop").position().top }, 100);
							}',
		'itemView'=>'_preview',
		'emptyText'=>'<div id="noEnquiriesHere">'.__('No enquiries here').'.</div>',
	));
}
?>
</div>

<div id="enquiry_popup" class="modal" style="width:870px;">
	<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/closeModal.png" />
	<img class="bModal2Page" onclick="js:enquiryModal2Page();" src="<?php echo Yii::app()->request->baseUrl; ?>/images/modal2page.png" />
<div id="enquiry_body"></div>
</div>


<div id="addressed_to_administration" style="display:none"><?php echo $model->getHumanStates(ENQUIRY_AWAITING_REPLY,ADMINISTRATION);?></div>
<div id="addressed_to_observatory" style="display:none"><?php echo $model->getHumanStates(ENQUIRY_AWAITING_REPLY,OBSERVATORY);?></div>

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
