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
	#options { display:none }   
	.outer{width:100%; padding: 0px; float: left;}
	.left {width: 38%; float: left; margin: 0px;}
	.right{width: 60%; float: left; margin: 0px;}
	.clear{clear:both;}
	.tag_enquiry_row_as_subscribed td:first-child { font-weight: bold }
</style>

<script>	
$(function() {
	//$(".left").stick_in_parent();
	$(".workflowFilter").on('click', function() {
			changeState($(this).attr('state'));
	});
});

function changeState(state){
	humanStates = <?php echo json_encode($model->getHumanStates()) ?>;
	$("#Enquiry_state").val(state);
	$("#search_enquiries").submit();
	
	$("#humanStateTitle").html("<?php echo __('Filtered by:').' ';?>"+humanStates[state]);
}

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
function toggleOptions(){
	if ( $("#options").is(":visible") ){
		$("#options").slideUp('fast');
		
	}else{
		$("#options").slideDown('fast');
		
	}
}

function resetForm(){
	$('#Enquiry_searchText').val('');
	$("#Enquiry_state").val('');
	$('#Enquiry_addressed_to').val('');
	$('#Enquiry_type').val('');
	$('#Enquiry_searchDate_min').val('');
	$('#Enquiry_searchDate_max').val('');
	$("#search_enquiries").submit();
}
function togglePane(el, pane){
	if(pane == 'states_pane'){
		resetForm();
	}
	$('#pane_items').find('div').removeClass('active');
	$('.pane').hide();
	$(el).addClass('active');
	$('#'+pane).show();
}
</script>

<h1>Big_button_1 Big_button_2 Big_button_3 Big_button_4</h1>
<?php if(Yii::app()->user->isGuest){
	echo '<div>';
		echo '<p></p>';
		echo '<p>'.__('Haz una consulta y aporta tu granito de arena.').' ';
		echo __('Más consultas significa más cooperación entre ciudadanos').' ';
		echo __('Aqui en el Observatorio nos encargamos de todo el papelaeo').' ';
			echo __('Remember you must first').' '.
				'<a href="'.Yii::app()->request->baseUrl.'/site/login">'.__('login').'</a>'.' '.__('or').' '.
				'<a href="'.Yii::app()->request->baseUrl.'/site/register">'.__('create an account').'</a>'.
				'</p>';
	echo '</div>';
} ?>

<div id="options">

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
</div>	<!-- options end -->

<div class="link" style="text-align:right" onCLick="js:toggleOptions();return false;">more options</div>
<div class="horizontalRule"></div>


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

<div id="enquiry" class="modal" style="width:870px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="enquiry_body"></div>
</div>

<div id="addressed_to_administration" style="display:none"><?php echo $model->getHumanStates(ENQUIRY_AWAITING_REPLY,ADMINISTRATION);?></div>
<div id="addressed_to_observatory" style="display:none"><?php echo $model->getHumanStates(ENQUIRY_AWAITING_REPLY,OBSERVATORY);?></div>

