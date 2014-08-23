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


<script>
function submitChange(id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/config/update/'+id,
		type: 'POST',
		//dataType: 'json',
		data: $('#config-form').serialize(),
		beforeSend: function(){ },
		complete: function(){ },
		success: function(data){
			alert('ok');
		},
		error: function() { alert("error on config/udapte"); },
	});
}
function updateParam(el){
	param = $(el).attr('param');
	value = $('#value_'+param).val();
	$('#Config_parameter').val(param);
	$('#Config_value').val(value);
	submitChange(param);
}
function updateBool(el){
	param = $(el).attr('param');
	value = $('input[name='+param+']:checked').val();
	$('#Config_parameter').val(param);
	$('#Config_value').val(value);
	submitChange(param);
}
</script>

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
		margin-top: 5px;
}
.param > .paramDescription {
	color: grey;
}
.param > label {
	color: green;
}
</style>

<?php
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'config-form',
		'enableAjaxValidation'=>false,
	));
	echo $form->hiddenField($model, 'parameter');
	echo $form->hiddenField($model, 'value');
	$this->endWidget();
?>

<h1><?php echo __('Global parameters');?></h1>



<div class="parameterGroup">
	<div class="parameterGroupTitle"><?php echo __('Administration');?></div>
	<div class="param">
		<?php $param = Config::model()->findByPk('administrationName'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('administrationLatitude'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('administrationLongitude'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
</div>

<div class="parameterGroup">
	<div class="parameterGroupTitle"><?php echo __('Locale');?></div>
	<div class="param">
		<?php $param = Config::model()->findByPk('currencySymbol'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('languages'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
</div>

<div class="parameterGroup">
	<div class="parameterGroupTitle"><?php echo __('SMTP');?></div>
	<div class="param">
		<?php $param = Config::model()->findByPk('smtpMethod'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input type="radio" name="smtpMethod" value="0" <?php echo ($param->value == 0) ? 'checked="checked"' : '' ?> />SMTP
		<input type="radio" name="smtpMethod" value="1" <?php echo ($param->value == 1) ? 'checked="checked"' : '' ?> />Sendmail
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateBool(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('smtpAuth'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input type="radio" name="smtpAuth" value="0" <?php echo ($param->value == 0) ? 'checked="checked"' : '' ?> />No
		<input type="radio" name="smtpAuth" value="1" <?php echo ($param->value == 1) ? 'checked="checked"' : '' ?> />Yes
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateBool(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('smtpHost'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('smtpPassword'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('smtpPort'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('smtpSecure'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('smtpUsername'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('emailNoReply'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
</div>
<div class="clear"></div>

<div class="parameterGroup">
	<div class="parameterGroupTitle"><?php echo __('Observatory');?></div>
	<div class="param">
		<?php $param = Config::model()->findByPk('observatoryName1'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('observatoryName2'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('siglas'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('observatoryBlog'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('emailContactAddress'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('telephone'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
</div>

<div class="parameterGroup">
	<div class="parameterGroupTitle"><?php echo __('Social networks');?></div>
	<div class="param">
		<?php $param = Config::model()->findByPk('socialFacebookURL'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('socialTwitterURL'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('socialActivateNonFree'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input type="radio" name="socialActivateNonFree" value="0" <?php echo ($param->value == 0) ? 'checked="checked"' : '' ?> />No
		<input type="radio" name="socialActivateNonFree" value="1" <?php echo ($param->value == 1) ? 'checked="checked"' : '' ?> />Yes
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateBool(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('socialTwitterUsername'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
</div>

<div class="parameterGroup">
	<div class="parameterGroupTitle"><?php echo __('Misc');?></div>
	<div class="param">
		<?php $param = Config::model()->findByPk('databaseDumpMethod'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input id="value_<?php echo $param->parameter;?>" type="text" value = "<?php echo $param->value;?>"/>
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateParam(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('membership'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input type="radio" name="membership" value="0" <?php echo ($param->value == 0) ? 'checked="checked"' : '' ?> />No
		<input type="radio" name="membership" value="1" <?php echo ($param->value == 1) ? 'checked="checked"' : '' ?> />Yes
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateBool(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('siteAutoBackup'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input type="radio" name="siteAutoBackup" value="0" <?php echo ($param->value == 0) ? 'checked="checked"' : '' ?> />No
		<input type="radio" name="siteAutoBackup" value="1" <?php echo ($param->value == 1) ? 'checked="checked"' : '' ?> />Yes
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateBool(this); return false;"/>
	</div>
	<div class="param">
		<?php $param = Config::model()->findByPk('safeHTMLeditor'); ?>
		<span class="paramDescription"><?php echo $param->description;?></span><br />
		<input type="radio" name="safeHTMLeditor" value="0" <?php echo ($param->value == 0) ? 'checked="checked"' : '' ?> />No
		<input type="radio" name="safeHTMLeditor" value="1" <?php echo ($param->value == 1) ? 'checked="checked"' : '' ?> />Yes
		<input type="button" value="save" param="<?php echo $param->parameter;?>" onClick="js:updateBool(this); return false;"/>
	</div>
</div>

<div class="clear"></div>

<?php
//$dataProvider=$model->search();
//$dataProvider->pagination->pageSize = $model->count();
?>

<?php 
	/*
	$this->widget('zii.widgets.grid.CGridView', array(
		'htmlOptions'=>array('class'=>'pgrid-view pgrid-cursor-pointer'),
		'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
		'id'=>'config-grid',
		'selectableRows'=>1,
		'selectionChanged'=>'function(id){ location.href = "'.$this->createUrl('update').'/"+$.fn.yiiGridView.getSelection(id);}',
		'dataProvider'=>$dataProvider,
		//'filter'=>$model,
		'columns'=>array(
			'parameter',
			'value',
			'description',
		),
	)); 
	*/
?>
