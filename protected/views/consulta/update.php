<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

$this->menu=array(
	array('label'=>'View consulta', 'url'=>array('/consulta/teamView', 'id'=>$model->id)),
	array('label'=>'Add reply', 'url'=>array('/respuesta/create?consulta='.$model->id)),
	array('label'=>'Edit consulta', 'url'=>array('/consulta/edit', 'id'=>$model->id)),
	array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
	array('label'=>'List consultas', 'url'=>array('/consulta/managed')),

	//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
?>

<h1>Change state</h1>

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

<h1>La consulta</h1>
<div class="view" style="padding:4px;">
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>
</div>







