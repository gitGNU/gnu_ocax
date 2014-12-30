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

if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
}else{
	echo '<link rel="stylesheet" type="text/css" href="'.Yii::app()->request->baseUrl.'/css/enquiry.css" />';
	echo '<link rel="stylesheet" type="text/css" href="'.Yii::app()->request->baseUrl.'/fonts/fontello/css/fontello.css" />';
	echo '<script src="'.Yii::app()->request->baseUrl.'/scripts/jquery.bpopup-0.9.4.min.js"></script>';
	echo $this->renderPartial('subscribeScript',array(),false,false);
}
?>

<?php if(Config::model()->findByPk('socialActivateNonFree')->value) { ?>
<script>
!function(d,s,id){
	var js,fjs=d.getElementsByTagName(s)[0];
	if(!d.getElementById(id)){
		js=d.createElement(s);
		js.id=id;
		js.src="https://platform.twitter.com/widgets.js";
		fjs.parentNode.insertBefore(js,fjs);
	}
}
(document,"script","twitter-wjs");
</script>

<div id="fb-root"></div>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<?php } ?>

<script>
function showBudget(budget_id, element){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getBudget/'+budget_id,
		type: 'GET',
		beforeSend: function(){
						$('.loading_gif').remove();
						$(element).after('<img style="vertical-align:middle;" class="loading_gif" src="<?php echo Yii::app()->request->baseUrl;?>/images/loading.gif" />');
					},
		complete: function(){ $('.loading_gif').remove(); },
		success: function(data){
			if(data != 0){
				$("#budget_popup_body").html(data);
				$('#budget_popup').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, speed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show budget");
		}
	});
}
function enquiryModal2Page(){
	$('#enquiry_popup').bPopup().close();
	window.open('<?php echo $this->createAbsoluteUrl('/e/'.$model->id); ?>',  '_blank');
}
</script>

<?php
	if(Yii::app()->request->isAjaxRequest)
		echo '<div class="modalTitle">'.__('Enquiry').'</div>';

	if($reformulatedDataprovider = $model->getReformulatedEnquires()){
		$this->renderPartial('//enquiry/_reformulated', array(	'dataProvider'=>$reformulatedDataprovider,
															'model'=>$model,
															'onClick'=>'/enquiry/view'));
	}
?>

<h1 id="enquiryTitle" <?php echo !Yii::app()->request->isAjaxRequest ? 'style="margin-top:-15px;"':'' ?>><?php echo $model->title?></h1>

<div id="enquiryDetails">
<?php
$attribs = array();
$attribs[] = array(
        'label'=>__('Formulated'),
		'type' => 'raw',
        'value'=>($model->user0->username == Yii::app()->user->id || $model->user0->is_disabled == 1) ?
					format_date($model->created).' '.__('by').' '.$model->user0->fullname :
					format_date($model->created).' '.__('by').' '.CHtml::link(
															CHtml::encode($model->user0->fullname), '#!',
															array('onclick'=>'js:getContactForm('.$model->user.');return false;')
														),
	);
$attribs[] = array(
		'label'=>__('State'),
		'type' => 'raw',
		'value'=> CHtml::encode($model->getHumanStates($model->state)),
	);
		
if($model->state >= ENQUIRY_AWAITING_REPLY){
	$submitted_info=format_date($model->submitted).', '.__('Registry number').': '.$model->registry_number;
	if($model->documentation)
		$submitted_info = '<a href="'.$model->documentation0->getWebPath().'" target="_new">'.$submitted_info.'</a>';
	$attribs[] = array(	'label'=>__('Submitted'),
						'type'=>'raw',
						'value'=>$submitted_info,
				);
}
$attribs[] = array(
		'label'=>__('Type'),
		'value'=>($model->related_to) ? $model->getHumanTypes($model->type).' ('.__('reformulated').')' : $model->getHumanTypes($model->type),
	);
$this->widget('zii.widgets.CDetailView', array(
	'id' => 'e_details',
	'cssFile' => Yii::app()->request->baseUrl.'/css/pdetailview.css',
	'data'=>$model,
	'attributes'=>$attribs,
));


if($model->budget)
	$this->renderPartial('_budgetDetails', array(	'model'=>$model->budget0,
													'showLinks'=>1,
													'showEnquiriesMadeLink'=>1,
													'enquiry'=>$model,
												));
?>

</div>	<!-- end enquiryDetails -->
<div>

<!-- socaial options start -->
<div id="socialOptions">
	<?php
	if($model->state >= ENQUIRY_ACCEPTED){
		$active='';
		if(EnquirySubscribe::model()->isUserSubscribed($model->id, Yii::app()->user->getUserID()))
			$active = "active";
		echo '<div style="float:left; cursor:pointer; padding-bottom:5px; position:relative" onClick="js:showSubscriptionNotice(this, '.$model->id.');">';
		echo '<span id="subscribe-icon_'.$model->id.'" class="email-subscribe subscribe-icon_'.$model->id.' '.$active.'"><i class="icon-mail"></i></span>';
		echo '<span class="subscriptionCount" id="subscriptionTotal">'.count($model->subscriptions).'</span>';
		echo '<span>'.__('Subscribed').'</span>';
		echo '</div>';
		echo '<div class="alert subscription_notice"></div>';

		if(Config::model()->findByPk('socialActivateNonFree')->value){
			echo '<div style="float:left;margin-left:10px;width:80px;">
				  <a	href="https://twitter.com/share"
						class="twitter-share-button"
						data-url="'.trim($this->createAbsoluteUrl('/e/'.$model->id)).'"
						data-counturl="'.trim($this->createAbsoluteUrl('/e/'.$model->id)).'"
						data-text="'.trim($model->title).'"
						data-via="'.trim(Config::model()->findByPk('socialTwitterUsername')->value).'"
						data-lang="en"
						>
				</a>
				</div>';
			echo '<div style="float:left;margin-left:10px;">
				  <div	class="fb-like"
						data-href="'.$this->createAbsoluteUrl('/e/'.$model->id).'"
						data-send="false"
						data-layout="button_count"
						data-width="80px"
						data-show-faces="false"
						data-font="arial">
				</div>
				</div>';
		}
	}?>

</div>
<br />
<!-- social options stop -->

<?php
if($model->state == ENQUIRY_PENDING_VALIDATION && $model->user == Yii::app()->user->getUserID()){
	echo '<div style="font-style:italic;margin-top:-30px;margin-bottom:10px;">'.__('You can').' '.
		 CHtml::link(__('edit the enquiry'),array('enquiry/edit','id'=>$model->id)).' '.__('and even').' '.
		 CHtml::link(__('delete it'),"#",
                    array(
						"submit"=>array('delete', 'id'=>$model->id),
						"params"=>array('returnUrl'=>Yii::app()->request->baseUrl.'/user/panel'),
						'confirm' => __('Are you sure?'))).
		 ' '.__('until it has been accepted by the observatory.').
		 '</div>';
}
?>

<?php echo $this->renderPartial('_view', array('model'=>$model)); ?>
</div>

<div class="clear"></div>


