<?php
/* @var $this PartidaController */
/* @var $model Partida */
/* @var $form CActiveForm */
?>

<style>
#parent_provision {
	float:left;
	font-size:1.4em;
	margin-left:10px;
}
</style>

<script>
function getProvision(el){
	if(! $(el).val()){
		$('#parent_provision').html('');
		return
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getProvision',
		type: 'GET',
		async: false,
		data: {'id': $(el).val() },
		beforeSend: function(){ /*$ ('#right_loading_gif').show(); */ },
		complete: function(){ /* $('#right_loading_gif').hide(); */ },
		success: function(data){
			$('#parent_provision').html('<b> = '+data+'</b>');
		},
		error: function() {
			alert("Error on get parent provision");
		}
	});
}
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'partida-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'parent'); ?>
		<?php
		$budgets_this_year = $model->findAll(array('condition'=>'year =  '.$model->year));
		$parent_budgets= CHtml::listData($budgets_this_year,'id',function($budget) {
			return CHtml::encode($budget->code.': '.$budget->concept);
		});
		echo '<div style="float:left">';
		echo $form->dropDownList($model, 'parent', $parent_budgets, array('prompt'=>'Partida raiz', 'onchange'=>'js:getProvision(this);'));
		echo '</div><div id="parent_provision"></div><div style="clear:both"></div>';
		?>
		<?php echo $form->error($model,'parent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'year'); ?>
		<?php echo $form->textField($model,'year'); ?>
		<?php echo $form->error($model,'year'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code'); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'concept'); ?>
		<?php echo $form->textField($model,'concept',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'concept'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'provision'); ?>
		<div class="hint">Cifra sin puntos y comas</div>
		<?php echo $form->textField($model,'provision'); ?>
		<?php echo $form->error($model,'provision'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'spent'); ?>
		<div class="hint">Cifra sin puntos y comas</div>
		<?php echo $form->textField($model,'spent'); ?>
		<?php echo $form->error($model,'spent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'weight'); ?>
		<div class="hint">Orden de esta partida en la lista de partidas (solo tiene valor estético)</div>
		<?php echo $form->textField($model,'weight'); ?>
		<?php echo $form->error($model,'weight'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
