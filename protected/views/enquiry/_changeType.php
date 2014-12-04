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
		<?php echo CHtml::submitButton(__('Save'));?>
		<input type="button" value="<?php echo __('Cancel')?>" onclick="js:window.history.back();" />
	</div>

</div>
<div class="right">

<div id="budget_details">
<?php
if($model->budget){
	$this->renderPartial('_budgetDetails', array('model'=>$model->budget0));
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
	'dataProvider'=>$budgetModel->changeTypeSearch(),
	'filter'=>$budgetModel,
	'columns'=>array(
		array(
			'header'=>__('Concept'),
			'name'=>'concept',
			'value'=>'Budget::model()->findByPk($data[\'id\'])->getConcept()',
		),
		'year',
		'code',
		'csv_id',
		array(
			'class'=>'CButtonColumn',
			'buttons' => array(
				'select' => array(
					'label'=> __('Choose budget'),
					'url'=> '"javascript:chooseBudget(\"".$data->id."\");"',
					'imageUrl' => Yii::app()->request->baseUrl.'/images/tick.png',
					'visible' => 'true',
				),
			),
			'template'=>'{select}',
		),
	),
)); ?>


<div class="horizontalRule" style="margin-top:20px">
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>
</div>
