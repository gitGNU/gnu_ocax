<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

//http://stackoverflow.com/questions/10188391/yii-renderpartial-proccessoutput-true-avoid-duplicate-js-request
if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
}
?>

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



<div class="view" style="float:right;text-align:left;margin-left:10px;padding:0px;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'created',
		array(
	        'label'=>__('Type'),
	        'value'=>($model->related_to) ? $model->humanTypeValues[$model->type].' ('.__('reformulated').')' : $model->humanTypeValues[$model->type],
		),
		array(
	        'label'=>__('State'),
			'type' => 'raw',
	        'value'=>$model->getHumanStates($model->state),

		),
	),
));
if($model->budget){
	$budget=Budget::model()->findByPk($model->budget);
	$this->renderPartial('//budget/_enquiryView', array('model'=>$budget));
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


