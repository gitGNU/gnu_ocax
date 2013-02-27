<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

$this->menu=array(
	array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'manager')),
	array('label'=>'Listar todas', 'url'=>array('admin')),
);
?>

<h1>Manage consulta</h1>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consulta-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php /*echo $form->label($model,'state'); */ ?>
		<?php /*echo $form->dropDownList($model, 'state', $model->humanStateValues);*/?>
	</div>

	<div class="row">
		<?php /*echo $form->label($model,'type');*/ ?>
		<?php /*echo $form->dropDownList($model, 'type', $model->humanTypeValues);*/ ?>
		<?php /*echo $form->error($model,'type');*/ ?>
	</div>

	<div class="row">
		<?php /* echo $form->labelEx($model,'capitulo'); */?>
		<?php /*echo $form->textField($model,'capitulo'); */?>
		<?php /*echo $form->error($model,'capitulo'); */?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'team_member'); ?>
		<div class="hint">Team member responsable for this Consulta</div>
		<?php
			$data=CHtml::listData($team_members,'id', 'fullname');
			echo $form->dropDownList($model, 'team_member', $data, array('prompt'=>'Not assigned'));
		?>
		<?php echo $form->error($model,'team_member'); ?>
	</div>


<div class="clear"></div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>


<?php $this->endWidget(); ?>
</div><!-- form -->

<h1>La consulta</h1>
<div class="view" style="padding:4px;">
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>
</div>

<?php if(Yii::app()->user->hasFlash('prompt_email')):?>
    <div class="flash_prompt">
		<p style="margin-top:5px;">Enviar un correo a las <b><?php echo Yii::app()->user->getFlash('prompt_email');?></b> personas suscritas a esta consulta?</p>
		<?php 
		$url=Yii::app()->request->baseUrl.'/email/create?consulta='.$model->id.'&menu=manager';
		?>
			<button onclick="js:window.location='<?php echo $url?>';">Sí</button>
			<button onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?>/consulta/admin';">No</button>
    </div>
<?php endif; ?>
