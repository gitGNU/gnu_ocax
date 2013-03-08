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

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consulta-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="form">

	<div class="title">Change state</div>

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php
			$dropDown_data = $model->getHumanStates();
			unset($dropDown_data[0]);
		?>
		<?php echo $form->dropDownList($model, 'state', $dropDown_data);?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Update');
		$cancelURL='/consulta/teamView/'.$model->id;
		?>
		<input type="button" value="Cancel" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />

	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<p></p>
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>








