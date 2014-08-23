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

/* @var $this ConfigController */
/* @var $model Config */
?>

<style>
.parameterGroup {
	float:left;
	padding:3px;
	margin-right:10px;
	margin-bottom:15px;
	border: solid 1px grey;
}
.parameterGroup > .parameterGroupTitle {
	font-size:1.2em;
}
.param {
		height:55px;
}
.param > .paramDescription {
	color: grey;
}
.param > label {
	color: green;
}
</style>
<script>
function submitChange(el, id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/config/update/'+id,
		type: 'POST',
		data: $('#config-form').serialize(),
		beforeSend: function(){
						$(el).hide();
						$(el).after('<img style="vertical-align:middle;" class="loading_gif" src="<?php echo Yii::app()->request->baseUrl;?>/images/loading.gif" />');
					},
		complete: function(){
						$('.loading_gif').hide();
						$(el).show();
					},
		success: function(data){
			return 1;
		},
		error: function() { alert("error on config/udapte"); },
	});
	return 0;
}
function updateParam(el){
	param = $(el).attr('param');
	value = $('#value_'+param).val();
	$('#Config_parameter').val(param);
	$('#Config_value').val(value);
	submitChange(el, param);
}
function updateBool(el){
	param = $(el).attr('param');
	value = $('input[name='+param+']:checked').val();
	$('#Config_parameter').val(param);
	$('#Config_value').val(value);
	submitChange(el, param);
}
</script>

<?php
$this->menu=array(
	array('label'=>__('Observatory'), 'url'=>array('observatory')),
	array('label'=>__('Administration'), 'url'=>array('administration')),	
	array('label'=>__('Email'), 'url'=>array('email')),
	array('label'=>__('Locale'), 'url'=>array('locale')),
	array('label'=>__('Social network'), 'url'=>array('social')),
	array('label'=>__('Misc'), 'url'=>array('misc')),
);
$this->inlineHelp=':profiles:cms_editor';
?>

<?php
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'config-form',
		'enableAjaxValidation'=>false,
	));
	echo $form->hiddenField($model, 'parameter');
	echo $form->hiddenField($model, 'value');
	$this->endWidget();
?>

<?php
	if(!isset($page))
		$page='summary';

	$this->renderPartial($page, array('model'=>$model));
?>
