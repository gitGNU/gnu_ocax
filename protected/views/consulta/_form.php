<?php
/* @var $this ConsultaController */
/* @var $model Consulta */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consulta-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'budget'); ?>


	<div class="row">
		<div id="budget_concept">
		<?php if($model->budget)
			$this->renderPartial('//budget/_consultaView',array('model'=>Budget::model()->findByPk($model->budget)));
		?>
		</div>

	</div>
	<div style="clear:both"></div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>

<?php
$this->widget('ext.tinymce.TinyMce', array(
    'model' => $model,
    'attribute' => 'body',
    // Optional config
    'compressorRoute' => 'tinyMce/compressor',
    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',

    'htmlOptions' => array(
        'rows' => 10,
        'cols' => 80,
    ),
));
?>

		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		<?php	if (!$model->id)
					$cancelURL='/user/panel';
				elseif ($model->team_member == Yii::app()->user->getUserID())
					$cancelURL='/consulta/teamView/'.$model->id;
				else
					$cancelURL='/consulta/'.$model->id;
		?>
		<input type="button" value="Cancelar" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


