<?php
/* @var $this PartidaController */
/* @var $model Partida */
/* @var $form CActiveForm */
?>

<?php
if($model->isNewRecord)
	$submitURL=Yii::app()->createUrl('budget/create');
else
	$submitURL=Yii::app()->createUrl('budget/update/'.$model->id);
?>

<script>
function submitBudget(){
	$.ajax({
		url: '<?php echo $submitURL; ?>',
		type: 'POST',
		async: false,
		//dataType: 'json',
		data: $('#budget-form').serialize(),
		beforeSend: function(){ /*$ ('#right_loading_gif').show(); */ },
		complete: function(){ /* $('#right_loading_gif').hide(); */ },
		success: function(data){
			if(data == 1){
				$('#form_container').hide();
				$('#saved_ok').show( function() {
				    setTimeout(function(){
				        $("#saved_ok").fadeOut('slow');
				    }, 2000);
				});
				$('#budget-grid').yiiGridView('update');
			}
		},
		error: function() {
			alert("Error on submitBudget");
		}
	});
}
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'budget-form',
	'enableAjaxValidation'=>true,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'year'); ?>
	<?php echo $form->hiddenField($model,'parent'); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'parent'); ?>
		<input type="text" value="<?php echo $parent_budget->code.': '.$parent_budget->concept;?>" size="60" disabled/>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'csv_id'); ?>
		<input type="text" value="<?php echo $parent_budget->csv_id;?>" size="60" disabled/>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'csv_parent_id'); ?>
		<input type="text" value="<?php echo $parent_budget->csv_parent_id;?>" size="60" disabled/>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<div class="hint">Solo números</div>
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
		<div class="hint">Orden de esta partida en el gráfico de partidas (solo tiene valor estético)</div>
		<?php echo $form->textField($model,'weight'); ?>
		<?php echo $form->error($model,'weight'); ?>
	</div>

	<div class="row buttons">
		<input type="button" value="Update" onClick="js:submitBudget();" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
