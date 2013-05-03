<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

//http://stackoverflow.com/questions/10188391/yii-renderpartial-proccessoutput-true-avoid-duplicate-js-request
if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
}
?>
<?php if(!Yii::app()->request->isAjaxRequest){?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<style>
	   	.bClose{
		cursor: pointer;
		position: absolute;
		right: -21px;
		top: -21px;
	}
</style>
<?php } ?>

<style>           
.clear{clear:both;}
.socialIcons {	margin:0px; }
.socialIcons img { cursor:pointer; }
#directlink span  { cursor:pointer; }
#directlink span:hover { color:black; }
</style>
<script>
function subscribe(el){
	if('1' == '<?php echo Yii::app()->user->isGuest;?>'){
		$(el).attr('checked', false);
		alert('Please login to subscribe');
		$('#subscribe').hide();
		return;
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/subscribe',
		type: 'POST',
		async: false,
		dataType: 'json',
		data: { 'enquiry': <?php echo $model->id;?> },
		//beforeSend: function(){ },
		//complete: function(){ },
		success: function(data){
			$('#subscribe').fadeOut();
		},
		error: function() { alert("error on subscribe"); },
	});
}
function toggleSocialPopup(id){
	if ( $('#'+id).is(':visible') )
		$('#'+id).hide();
	else{
		$('.social_popup').hide();
		$('#'+id).show();
	}
}
function toggleStatesDiagram(){
	if ( $('#states_diagram').is(':visible') )
		$('#states_diagram').slideUp('fast');
	else{
		$('#states_diagram').slideDown('fast');
	}
}
function getContactForm(recipient_id){
	if(!isUser())
		return;
	if(!canParticipate())
		return;
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/email/contactPetition',
		type: 'GET',
		async: false,
		data: {'recipient_id': recipient_id, 'enquiry_id': <?php echo $model->id?> },
		beforeSend: function(){ },
		complete: function(){ /* $('#right_loading_gif').hide(); */ },
		success: function(data){
			if(data != 1){
				$('#contact_petition_content').html();
				$("#contact_petition_content").html(data);
				$('#contact_petition').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on get Contact petition");
		}
	});
}
function sendContactForm(form){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/email/contactPetition',
		type: 'POST',
		async: false,
		data: $('#'+form).serialize(),
		beforeSend: function(){
					$('#contact_petition_buttons').replaceWith($('#contact_petition_sending'));
					$('#contact_petition_sending').show();
					},
		complete: function(){ /* $('#right_loading_gif').hide(); */ },
		success: function(data){
			if(data == 1){
				$('#contact_petition_sending').replaceWith($('#contact_petition_sent'));
				$('#contact_petition_sent').show();

			}else{
				$('#contact_petition_sending').replaceWith($('#contact_petition_error'));
				$('#contact_petition_error').html(data);
				$('#contact_petition_error').show();
			}
			setTimeout(function() {
				$('#contact_petition').fadeOut('fast',
										function(){
											$('#contact_petition').bPopup().close();
									});
    		}, 2000);
		},
		error: function() {
			alert("Error on post Contact petition");
		}
	});
}
function showBudgetDescription(budget_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getBudgetDescription/'+budget_id,
		type: 'GET',
		async: false,
		//dataType: 'json',
		beforeSend: function(){ },
		success: function(data){
			$('#budget_description_body').html(data);
			$('#budget_description').bPopup({
				modalClose: false
				, follow: ([false,false])
				, fadeSpeed: 10
				, positionStyle: 'absolute'
				, modelColor: '#ae34d5'
			});
		},
		error: function() {
			alert("Error on get budget description");
		}
	});
}
</script>

<?php if($reformulatedDataprovider = $model->getReformulatedEnquires()){
$providerData = $reformulatedDataprovider->getData();

echo '<style>.highlight_row{background:#FFDEAD;}</style>';
echo 	'<div style="font-size:1.3em">'.__('The enquiry').' "'.$providerData[0]->title.'" '.__('has been reformulated').
		' '. (count($providerData)-1) .' '.__('time(s)').'</div>';

$this->widget('PGridView', array(
	'id'=>'reforumulated-enquiry-grid',
	'dataProvider'=>$reformulatedDataprovider,
	'template' => '{items}{pager}',
	'rowCssClassExpression'=>'($data->id == '.$model->id.')? "highlight_row":"row_id_".$row." ".($row%2?"even":"odd")',
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/enquiry/view',
    ),
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
			array(
				'header'=>__('Enquiry'),
				'value'=>'$data[\'title\']',
			),
			array(
				'header'=>__('State'),
				'type' => 'raw',
				'value'=>'$data->getHumanStates($data[\'state\'])',
			),
			array(
				'header'=>__('Formulated'),
				'value'=>'$data[\'created\']',
			),
			array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
}?>

<h1><?php echo $model->title?></h1>
<hr style="margin-top:-10px;margin-bottom:-5px;" />
<div id="states_diagram" style="display:none;z-index:10;position:absolute;">
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/states.png" onClick="js:toggleStatesDiagram();"/>
</div>

<div class="view" style="float:right;text-align:left;margin-left:10px;padding:0px;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(


//if($data->user0->username != Yii::app()->user->id)
			//echo '<span class="link" onClick="js:getContactForm('.$data->user.')">';

		array(
	        'label'=>__('Formulated'),
			'type' => 'raw',
	        'value'=>($model->user0->username == Yii::app()->user->id) ?
						$model->created.' '.__('by').' '.$model->user0->fullname :
						$model->created.' '.__('by').' '.CHtml::link(
															CHtml::encode($model->user0->fullname), '#',
															array('onclick'=>'js:getContactForm('.$model->user.');')
														),
		),
		array(
	        'label'=>__('Type'),
	        'value'=>($model->related_to) ? $model->getHumanTypes($model->type).' ('.__('reformulated').')' : $model->getHumanTypes($model->type),
		),
		array(
	        'label'=>__('Subscribed users'),
	        'value'=>count($model->subscriptions),
		),
		array(
	        'label'=>__('State'),
			'type' => 'raw',
			'value'=> CHtml::link(
						CHtml::encode($model->getHumanStates($model->state)), '#',
						array('onclick'=>'js:toggleStatesDiagram();')
					),
		),
	),
));

if($model->state >= 4){
	$file=File::model()->findByAttributes(array('model'=>'Enquiry','model_id'=>$model->id));
	$link='<a href="'.$file->webPath.'" target="_new">'.$file->name.'</a>';
	$submitted_info=$model->submitted.' '.__('Registry number').':'.$model->registry_number;

	$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>__('Submitted'),
			'type'=>'raw',
	        'value'=>$submitted_info,
		),
		array(
	        'label'=>__('Documentation'),
			'type'=>'raw',
	        'value'=>$link,
		),
	),
	));
}


if($model->budget){
	$budget=Budget::model()->findByPk($model->budget);
	$this->renderPartial('//budget/_enquiryView', array('model'=>$budget, 'showLinks'=>1));
}
?>
</div>

<div>
<div class="socialIcons" style="margin-top:10px;margin-bottom:10px;">
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/link.png" onClick="js:toggleSocialPopup('directlink');"/>
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/mail.png" onClick="js:toggleSocialPopup('subscribe');"/>
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/facebook.png" />
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/twitter.png" />
</div>

<div id="subscribe" style="	display :none;
							position: absolute;
							padding:5px;
							z-index: 1;
							width: 400px;
							background-color: #98FB98;
							"
	class="social_popup">
<?php
	$criteria = new CDbCriteria;
	$criteria->condition = 'enquiry = '.$model->id.' AND user = '.Yii::app()->user->getUserID();
	$checked = '';
	if( EnquirySubscribe::model()->findAll($criteria) )
			$checked = 'checked';
?>
<?php echo __('Keep me informed via email when there are changes')?>
<input type="checkbox"	onClick="js:subscribe(this);"
						style="
						    vertical-align: middle;
						    position: relative;
						    bottom: 1px;
						"
	<?php echo $checked; ?>
/>
</div>

<div id="directlink" style="display :none;
							position: absolute;
							padding:5px;
							z-index: 1;
							width: 400px;
							background-color: #98FB98;
							"
	class="social_popup">
<?php
	$url = $this->createAbsoluteUrl('/enquiry/'.$model->id);
	//$url=Yii::app()->request->baseUrl.'/enquiry/'.$model->id;
	echo '<span onClick=\'location.href="'.$url.'";\'>'.$url.'</span>';
?>
</div>

<?php
if($model->state == 1 && $model->user == Yii::app()->user->getUserID()){
	echo '<div style="font-style:italic;">Puedes '.CHtml::link('editar la enquiry',array('enquiry/edit','id'=>$model->id)).' y incluso ';
	echo CHtml::link('borrarla',"#",
                    array(
						"submit"=>array('delete', 'id'=>$model->id),
						"params"=>array('returnUrl'=>Yii::app()->request->baseUrl.'/user/panel'),
						'confirm' => '¿Estás seguro?'));
	echo ' hasta que la '.Config::model()->findByPk('siglas')->value.' reconozca la entrega. (+ comments and subscriptions).</div>';
}
?>

<?php echo $this->renderPartial('_view', array('model'=>$model,'replys'=>$replys)); ?>
</div>
<div class="clear"></div>

<div id="budget_description" style="display:none;width:700px;">
<div style="background-color:white;padding:10px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="budget_description_body"></div>
</div>
<p>&nbsp;</p>
</div>


