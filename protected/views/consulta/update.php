<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

$this->menu=array(
	array('label'=>'Ver consulta', 'url'=>array('/consulta/teamView', 'id'=>$model->id)),
	array('label'=>'Anadir respuesta', 'url'=>array('/respuesta/create?consulta='.$model->id)),
	array('label'=>'Editar consulta', 'url'=>array('edit', 'id'=>$model->id)),
	array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
	array('label'=>'Listar consultas', 'url'=>array('managed')),
	//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
?>


<div class="consulta">
<h1>Canvia estat</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consulta-form',
	'enableAjaxValidation'=>false,
)); ?>


<div class="form">

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php echo $form->dropDownList($model, 'state', $model->humanStateValues);?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Actualitzar');
		$cancelURL='/consulta/teamView/'.$model->id;
		?>
		<input type="button" value="Cancelar" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />

	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>

</div>







