<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

$this->menu=array(
	array('label'=>'Ver consulta', 'url'=>array('/consulta/adminView', 'id'=>$model->id)),
	array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'manager')),
	array('label'=>'Listar todas', 'url'=>array('admin')),
);
?>

<div class="consulta">
<h1>Gestionar consulta</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consulta-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php echo $form->dropDownList($model, 'state', $model->humanStateValues);?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'type'); ?>
		<?php echo $form->dropDownList($model, 'type', $model->humanTypeValues);?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php /* echo $form->labelEx($model,'capitulo'); */?>
		<?php /*echo $form->textField($model,'capitulo'); */?>
		<?php /*echo $form->error($model,'capitulo'); */?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'team_member'); ?>
		<?php
			$data=CHtml::listData($team_members,'id', 'fullname');
			echo $form->dropDownList($model, 'team_member', $data, array('prompt'=>'Sin asignar'));
		?>
		<?php echo $form->error($model,'team_member'); ?>
	</div>


<div class="clear"></div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Actualitzar'); ?>
	</div>


<?php $this->endWidget(); ?>
</div><!-- form -->


<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>

</div>
