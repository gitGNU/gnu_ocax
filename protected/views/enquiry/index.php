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

Yii::app()->clientScript->registerScript('search', "
$('#search-options-button').click(function(){
	$('#search-options').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiListView.update('enquiry-list', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/enquiry.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.sticky-kit.min.js"></script>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 38%; float: left;  margin: 0px;}
	.right{width: 60%; float: left; margin: 0px;}
	.clear{clear:both;}
	.tag_enquiry_row_as_subscribed td:first-child { font-weight: bold }
</style>

<script>	
$(function() {
	$(".left").stick_in_parent();

	$(".workflowText").on('click', function() {
		if( $(this).attr('state') ){
			changeState($(this).attr('state'));
		}
	});
});

function changeState(state){
	humanStates = <?php echo json_encode($model->getHumanStates()) ?>;
	$("#Enquiry_state").val(state);
	$("#search_enquiries").submit();
	
	$("#humanStateTitle").html("<?php echo __('Filtered by:').' ';?>"+humanStates[state]);
}

/*
function toggleState(el){
	if(!($(el).prop('checked'))){
		$('#Enquiry_addressed_to').val('');
	}
	else if($(el).val() == 0){
		$(':checkbox').not(el).attr('checked', false);
		$('#Enquiry_addressed_to').val(0);
		$('#addressed_to').html($('#addressed_to_administration').html());	// workflow diagram
	}else{
		$(':checkbox').not(el).attr('checked', false);
		$('#Enquiry_addressed_to').val(1);
		$('#addressed_to').html($('#addressed_to_observatory').html());	// workflow diagram
	}
	$("#search_enquiries").submit();
}
*/

function showEnquiry(enquiry_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/getEnquiry/'+enquiry_id,
		type: 'GET',
		dataType: 'json',
		beforeSend: function(){ $('#enquiry-grid').addClass('pgrid-view-loading'); },
		complete: function(){ 
						$('#enquiry-grid').removeClass('pgrid-view-loading');
						FB.XFBML.parse();
						twttr.widgets.load();
					},
		success: function(data){
			if(data != 0){
				$("#enquiry_body").html(data.html);
				$('#enquiry').bPopup({
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
function resetForm(){
	$('#Enquiry_addressed_to').val('');
	$("#Enquiry_state").val('');
	$("#search_enquiries").submit();
}
function togglePane(el, pane){
	//resetForm();
	$('#pane_items').find('div').removeClass('active');
	$('.pane').hide();
	$(el).addClass('active');
	$('#'+pane).show();
}
</script>

<div class="outer" style="position:relative">

<div class="left">
<?php
	//if(Yii::app()->user->isGuest){
		echo '<h1>'.CHtml::link(__('Formulate a new enquiry'),array('enquiry/create/')).'</h1>';
		echo '<p style="line-height:90%">'.__('Remember you must first').' '.
			 '<a href="'.Yii::app()->request->baseUrl.'/site/login">'.__('login').'</a>'.' '.__('or').' '.
			 '<a href="'.Yii::app()->request->baseUrl.'/site/register">'.__('create an account').'</a>'.
			 '</p>';

	//}
?>

<style>
.filterPaneMenuItem{
	cursor:pointer;
	float:left;
	background-color: #f5f1ed;
	font-size: 16px;
	margin-left:30px;
	padding: 0 8px 0 8px;



}
.filterPaneMenuItem.active{
	border: 1px solid lightgrey;
	margin-bottom:-1px;
	border-bottom:0px;	
}
</style>

<div id="pane_items" style="border-bottom: 1px solid lightgrey;">
<div class="filterPaneMenuItem active" onclick="js:togglePane(this, 'states_pane');"><?php echo __('States');?></div>
<div class="filterPaneMenuItem" onclick="js:togglePane(this, 'search_pane');"><?php echo __('Search');?></div>
<div class="clear"></div>
</div>

<div id="states_pane" class="pane">
	<div id="workflow" style="padding-bottom:5px;">
		<p style="text-align:center;margin-top:30px;">
		<?php echo __('What are the different states of an enquiry?');?>
		</p>
		<div>
		<?php $this->renderPartial('workflow',array('model'=>$model));?>
		</div>
	</div>
</div>

<div id="search_pane" class="pane" style="display:none">
	<?php if(count($model->publicSearch()->getData()) > 0 ){ ?>
		<div class="search-form">
			<?php $this->renderPartial('_searchPublic',array(
				'model'=>$model,
			)); ?>
		</div><!-- search-form -->
	<?php } ?>
</div>

</div>
<div class="right">
<h1><?php echo __('Enquiries made to date');?></h1>
<p style="margin-top:-15px;margin-bottom:0px;"><?php echo __('This is a list of enquiries made by citizens like you.');?>
<br /><br />
<span id="humanStateTitle"></span>
</p>

<?php
$this->widget('zii.widgets.CListView', array(
	'id'=>'enquiry-list',
	//'template'=>'{items}<div style="clear:both"></div>{pager}',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_preview',
	'emptyText'=>'<div id="noEnquiriesHere">'.__('No enquiries here').'.</div>',
));
?>

</div>
</div>

<div class="clear"></div>

<div id="enquiry" class="modal" style="width:870px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="enquiry_body"></div>
</div>

<div id="addressed_to_administration" style="display:none"><?php echo $model->getHumanStates(ENQUIRY_AWAITING_REPLY,ADMINISTRATION);?></div>
<div id="addressed_to_observatory" style="display:none"><?php echo $model->getHumanStates(ENQUIRY_AWAITING_REPLY,OBSERVATORY);?></div>
