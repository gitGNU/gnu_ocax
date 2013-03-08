<?php
/* @var $this ConsultaController */
/* @var $model Consulta */
/* @var $form CActiveForm */

$user_id = Yii::app()->user->getUserID();
$user= User::model()->findByPk($user_id);
if(!$user->is_active)
	$this->renderPartial('//user/_notActiveInfo', array('model'=>$user));
?>

<script>
$(document).ready(function() {
	if(1 != <?php echo $user->is_active;?>){
		$('#consulta-form').find(':input:not(:disabled)').prop('disabled',true);
		$('#consulta-form').find(':textarea:not(:disabled)').prop('disabled',true);
	}
});
</script>


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consulta-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title"><?php echo $model->isNewRecord ? 'Nueva consulta' : 'Cambiar consulta'?></div>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'budget'); ?>


	<?php if($model->budget){
		echo '<div class="row" style="margin:-15px -10px 10px -10px;">';
		echo '<div id="budget_concept">';
		$this->renderPartial('//budget/_consultaView',array('model'=>Budget::model()->findByPk($model->budget)));
		echo '</div>';
	}?>

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
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Publicar' : 'Actualizar'); ?>
		<?php	if (!$model->id)
					$cancelURL='/user/panel';
				elseif ($model->team_member == $user_id)	// remember: a team_memebr can edit a consulta
					$cancelURL='/consulta/teamView/'.$model->id;
				else
					$cancelURL='/consulta/'.$model->id;
		?>
		<input type="button" value="Cancelar" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


