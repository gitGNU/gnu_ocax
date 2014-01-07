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

if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
}
if(!Yii::app()->request->isAjaxRequest){?>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>
<?php } ?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/fonts/fontello/css/fontello.css" />
<style>
i[class^="icon-"]:before, i[class*=" icon-"]:before {
	margin-top:0px;
	margin-right:3px;
	font-size:	17px;
}
#enquiryDetails tr:first-child td{border:none;}
#enquiryDetails tr:first-child th{border:none;}
</style>

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


<script>
function subscribe(el){
	if('1' == '<?php echo Yii::app()->user->isGuest;?>'){
		$(el).attr('checked', false);
		alert("<?php echo __('Please login to subscribe')?>");
		$('#subscribe').hide();
		return;
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/subscribe',
		type: 'POST',
		dataType: 'json',
		data: { 'enquiry': <?php echo $model->id;?>,
				'subscribe': $(el).is(':checked'),
			  },
		//beforeSend: function(){ },
		//complete: function(){ },
		success: function(data){
			$('#subscribe').slideUp('fast');
			if($('#subscriptionTotal').length>0){
				updateSubscriptionTotal(data);
			}
		},
		error: function() { alert("error on subscribe"); },
	});
}
function clickSocialIcon(el){
	if( $(el).attr('social_icon') ){
		$('#'+$(el).attr('social_icon')).show();
	}
}
$(function() {
	$('.social_popup').mouseleave(function() {
		$('.social_popup').fadeOut('fast');
	});
});

function toggleStatesDiagram(){
	$('#states_diagram').toggle();
	return false;
}


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
</script>

<?php if($reformulatedDataprovider = $model->getReformulatedEnquires()){
	$this->renderPartial('//enquiry/_reformulated', array(	'dataProvider'=>$reformulatedDataprovider,
															'model'=>$model,
															'onClick'=>'/enquiry/view'));
}?>

<h1 style="margin-bottom:-2px;"><?php echo $model->title?></h1>

<div	id="states_diagram" 
		style="	display:none;
				cursor:pointer;
				padding:20px;
				border: 1px solid grey;
				z-index:10;
				position:absolute;
				background-color:white;
				margin-left:10px;
				margin-top:10px;
				width:350px"
		onClick="$(this).toggle();return false;"		
>
<img	style="	cursor: pointer;
				position: absolute;
				right: -21px;
				top: -21px;"
		src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png";
/>
<?php $this->renderPartial('workflow',array('model'=>$model));?>

</div>

<div style="float:right;margin-top:5px;text-align:left;margin-left:5px;padding:0px;width:470px;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'id' => 'enquiryDetails',
	'cssFile' => Yii::app()->request->baseUrl.'/css/pdetailview.css',
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>__('Formulated'),
			'type' => 'raw',
	        'value'=>($model->user0->username == Yii::app()->user->id || $model->user0->is_disabled == 1) ?
						format_date($model->created).' '.__('by').' '.$model->user0->fullname :
						format_date($model->created).' '.__('by').' '.CHtml::link(
															CHtml::encode($model->user0->fullname), '#!',
															array('onclick'=>'js:getContactForm('.$model->user.');return false;')
														),
		),
		array(
	        'label'=>__('Type'),
	        'value'=>($model->related_to) ? $model->getHumanTypes($model->type).' ('.__('reformulated').')' : $model->getHumanTypes($model->type),
		),
		array(
	        'label'=>__('State'),
			'type' => 'raw',
			'value'=> CHtml::link(
						CHtml::encode($model->getHumanStates($model->state)), 'javascript:void(0);',
						array('onclick'=>'toggleStatesDiagram(); return false;')
					),
			//'value'=>$model->getHumanStates($model->state),
		),
	),
));

if($model->state >= ENQUIRY_AWAITING_REPLY){
	$submitted_info=format_date($model->submitted).', '.__('Registry number').': '.$model->registry_number;
	$attributes=array(
					array(
	        			'label'=>__('Submitted'),
						'type'=>'raw',
						'value'=>$submitted_info,
					),
				);
	if($model->documentation){
		$document = '<a href="'.$model->documentation0->getWebPath().'" target="_new">'.$model->documentation0->name.'</a>';
		$attributes[]=array(
				        'label'=>__('Documentation'),
						'type'=>'raw',
	        			'value'=>$document,
					);
	}
	$this->widget('zii.widgets.CDetailView', array(
		'cssFile' => Yii::app()->request->baseUrl.'/css/pdetailview.css',
		'data'=>$model,
		'attributes'=>$attributes,
	));
}
if($model->budget)
	$this->renderPartial('//budget/_enquiryView', array('model'=>$model->budget0, 'showLinks'=>1, 'showEnquiriesMadeLink'=>1, 'enquiry'=>$model));
?>

</div>	<!-- end float right -->
<div>

<!-- socaial options start -->
<div style="padding: 10px 00px 10px 0px; width:400px;margin-top:5px;">

	<div id="directlink" class="social_popup">
		<?php
		$url = $this->createAbsoluteUrl('/enquiry/'.$model->id);
		echo '<span style="cursor:pointer;" onClick=\'location.href="'.$url.'";\'>'.$url.'</span>';
		?>
	</div>

	<div id="subscribe" class="social_popup">
		<?php
			$criteria = new CDbCriteria;
			$criteria->condition = 'enquiry = '.$model->id.' AND user = '.Yii::app()->user->getUserID();
			$checked = '';
			if( EnquirySubscribe::model()->findAll($criteria) )
				$checked = 'checked';
		?>
		<?php echo __('Keep me informed via email when there are changes')?>
			<input	id="subscribe_checkbox"
					type="checkbox"
					onClick="js:subscribe(this);"
					style="
					    vertical-align: middle;
					    position: relative;
					    bottom: 1px;
					"
					<?php echo $checked; ?>
			/>
	</div>


	<?php
	if($model->state >= ENQUIRY_ACCEPTED){
		echo '<span style="float:left;margin-right:10px" class="ocaxButton" onClick="js:clickSocialIcon(this);" social_icon="directlink">'.
		'<i class="icon-link"></i>'.__('Direct link').'</span>';
		echo '<span style="float:left" class="ocaxButton" onClick="js:clickSocialIcon(this);" social_icon="subscribe">'.
		'<i class="icon-mail"></i>'.__('Subscribe').'</span>';
		echo '<span style="float:left" class="ocaxButtonCount" id="subscriptionTotal">'.count($model->subscriptions).'</span>';

		echo '<div style="float:left;margin-left:10px;width:80px;">
			  <a	href="https://twitter.com/share"
					class="twitter-share-button"
					data-url="'.$this->createAbsoluteUrl('/enquiry/'.$model->id).'"
					data-counturl="'.$this->createAbsoluteUrl('/enquiry/'.$model->id).'"
					data-text="'.$model->title.'"
					data-hashtags="'.Config::model()->findByPk('siglas')->value.'"
					data-lang="en"
					>
			</a>
			</div>';	

		echo '<div style="float:left;margin-left:10px;">
			  <div	class="fb-like"
					data-href="'.$this->createAbsoluteUrl('/enquiry/'.$model->id).'"
					data-send="false"
					data-layout="button_count"
					data-width="80px"
					data-show-faces="false"
					data-font="arial">
			</div>
			</div>';
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


