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

$this->menu=array(
	array('label'=>__('View enquiry'), 'url'=>array('/enquiry/teamView', 'id'=>$model->id)),
	array('label'=>__('List enquiries'), 'url'=>array('/enquiry/managed')),
);
$this->inlineHelp=':profiles:team_member';
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 58%; float: left;  margin: 0px;}
	.right{width: 38%; float: left; margin: 0px;}
	.clear{clear:both;}
	.icon{cursor:pointer;vertical-align:bottom;}
</style>

<script>
function deleteDoc(){
	location.href='<?php echo Yii::app()->request->baseUrl; ?>/enquiry/unSubmit/<?php echo $model->id;?>';
}
</script>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
)); ?>
<div class="form">

	<div class="title">
	<?php
		if($model->state < ENQUIRY_AWAITING_REPLY)
			echo __('Submit enquiry');
		else
			echo __('Correct submission');
	?>
	</div>

<div class="outer">
<div class="left">

	<div class="row">
		<?php echo $form->label($model,'submitted'); ?>
		<div class="hint"><?php echo __('Date the Enquiry was submitted to the Administration');?></div>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'model' => $model,
					'name'=>'Enquiry[submitted]',
					'value'=>$model->submitted,
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'yy-mm-dd',
					),
					'htmlOptions'=>array(
						'style'=>'height:20px;',
						'readonly'=>'readonly',
					),
		)); ?>
		<?php echo '<div class="errorMessage" id="Enquiry_submitted_em_" style="display:none"></div>'?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'registry_number'); ?>
		<?php echo $form->textField($model,'registry_number'); ?>
		<?php echo '<div class="errorMessage" id="Enquiry_registry_number_em_" style="display:none"></div>'?>
	</div>
	<p></p>

	<div class="row buttons">
	<?php
		if($model->documentation){
			echo '<div>'.__('Is this information correct?').'</div>';
			$name=__('Submit');
		}else
			$name=__('Add document');

		if($model->documentation){
			echo CHtml::submitButton($name);
		}
		if(!$model->documentation){
			// http://www.yiiframework.com/forum/index.php/topic/37075-form-validation-with-ajaxsubmitbutton/
			echo CHtml::ajaxSubmitButton(($name),CHtml::normalizeUrl(array('enquiry/submit/'.$model->id)),
				array(
					'dataType'=>'json',
					'type'=>'post',
					'success'=>'function(data) {
						$(".errorMessage").hide();
						if(data.status=="success"){
							uploadFile("Enquiry",'.$model->id.');
						}
						else{
							$.each(data, function(key, val) {
								$("#enquiry-form #"+key+"_em_").text(val);                                                    
								$("#enquiry-form #"+key+"_em_").show();
							});
						}       
					}',                    
					'beforeSend'=>'function(){ }',
				),
				array('id'=>'present_step1','style'=>'margin-right:20px;')
			);
		}
	?>
	</div>

</div>
<div class="right">

	<?php if($model->documentation){ ?>
	<div class="row">
		<div style="margin-bottom:5px;font-weight:bold;"><?php echo __('Documentation');?></div>
		<a href="<?php echo $model->documentation0->getWebPath();?>" target="_new"><?php echo $model->documentation0->name;?></a>
		<img class="icon" src="<?php echo Yii::app()->request->baseUrl;?>/images/delete.png" onClick="js:deleteDoc()" />
	</div>
	<?php } ?>

</div>
</div>
<div class="clear"></div>


<?php $this->endWidget(); ?>
</div><!-- form -->

<p></p>
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>


