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

	<div class="title"><?php echo $title;?></div>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'year'); ?>
	<?php echo $form->hiddenField($model,'parent'); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'csv_id'); ?>
		<?php echo $form->textField($model,'csv_id'); ?>
		<?php echo $form->error($model,'csv_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'csv_parent_id'); ?>
		<?php echo $form->textField($model,'csv_parent_id'); ?>
		<?php echo $form->error($model,'csv_parent_id'); ?>
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
		<input type="button" value="<?php echo $model->isNewRecord ? 'Create' : 'Update'?>" onClick="js:submitBudget();" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
