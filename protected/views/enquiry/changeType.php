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
	array('label'=>__('Edit enquiry'), 'url'=>array('/enquiry/edit', 'id'=>$model->id)),
	array('label'=>__('List enquiries'), 'url'=>array('/enquiry/managed')),
);

?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 28%; float: left;  margin: 0px;}
	.right{width: 68%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<script>
function changeType(el){
	type=$(el).val();
	if(type == 0){
		$('#Enquiry_type').val(type)
		$('#budget_details').hide();
	}else{
		if($('#Enquiry_budget').val() != ''){
			$('#budget_details').show();
		}else{
			$('#Enquiry_type_0').prop("checked",true);
			$('#Enquiry_type_1').prop("checked",false);
			alert("<?php echo __('Select a budget from the grid below')?>");
		}
	}
}
function chooseBudget(budget_id){
	$('#Enquiry_budget').val(budget_id);
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getBudgetDetails/'+budget_id,
		type: 'GET',
		dataType: 'json',
		beforeSend: function(){ },
		success: function(data){
			$('#budget_details').html(data);
			$('#budget_details').show();
			$('#Enquiry_type_0').prop("checked",false);
			$('#Enquiry_type_1').prop("checked",true);
		},
		error: function() {
			alert("Error on get Budget details");
		}
	});
}
</script>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="form">

	<div class="title"><?php echo __('Change type')?></div>

<div class="outer">
<div class="left">

	<?php echo $form->hiddenField($model,'budget'); ?>
	<div class="row">
		<?php echo $form->label($model,'type'); ?>
		<?php
			$dropDown_data = $model->getHumanTypes();
			unset($dropDown_data[2]);	// remove 'Reclamation' type
		?>
		<div class="hint"><?php echo __('Change type')?></div>
		<?php echo $form->radioButtonList($model, 'type', $dropDown_data,
										array(	'labelOptions'=>array('style'=>'display:inline'),
												'onchange'=>'changeType(this);'
										));
		?>
	</div>
	<p></p>
	<div class="row buttons">
		<?php
		echo CHtml::submitButton(__('Save'));
		$cancelURL='/enquiry/edit/'.$model->id;
		?>
		<input type="button" value="<?php echo __('Cancel')?>" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />

	</div>

</div>
<div class="right">

<div id="budget_details">
<?php
if($model->budget){
	$this->renderPartial('//budget/_enquiryView', array('model'=>$model->budget0));
}
?>
</div>

</div>
</div>
<div class="clear"></div>


<?php $this->endWidget(); ?>
</div><!-- form -->


<?php
	$this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
	'loadingCssClass'=>'pgrid-view-loading',
	'id'=>'budget-grid',
	'dataProvider'=>$filterBudgetModel->changeTypeSearch(),
	'filter'=>$filterBudgetModel,
	'columns'=>array(
		'year',
		'code',
		array(
			'header'=>__('Concept'),
			'name'=>'concept',
			'value'=>'Budget::model()->findByPk($data[\'id\'])->getConcept()',
		),
		array(
			'class'=>'CButtonColumn',
			'buttons' => array(
				'select' => array(
					'label'=> __('Choose budget'),
					'url'=> '"javascript:chooseBudget(\"".$data->id."\");"',
					'imageUrl' => Yii::app()->request->baseUrl.'/images/tick.png',
					'visible' => 'true',
				)

			),
			'template'=>'{select}',
		),
	),
)); ?>


<p></p>
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>


