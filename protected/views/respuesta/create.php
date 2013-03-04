<?php
/* @var $this RespuestaController */
/* @var $model Respuesta */
/* @var $form CActiveForm */

$this->menu=array(
	array('label'=>'Ver Consulta', 'url'=>array('/consulta/teamView', 'id'=>$consulta->id)),
	array('label'=>'Actualizar estat', 'url'=>array('/consulta/update', 'id'=>$consulta->id)),
	array('label'=>'Editar Consulta', 'url'=>array('/consulta/edit', 'id'=>$consulta->id)),
	array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$consulta->id, 'menu'=>'team')),
	array('label'=>'Listar consultas', 'url'=>array('/consulta/managed')),
	//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
?>

<style>
.form{
	-webkit-border-radius:10px;
	border-radius:10px;
	background-color:#D9CCB9;
	padding:10px;
	margin-bottom:10px;

}
</style>

<h1>Add reply</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	//'id'=>'respuesta-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->hiddenField($model,'consulta');?>

	<div class="row">
		<?php echo $form->label($consulta,'state');?>
		<?php $model->state=$consulta->state;?>
		<?php echo $form->dropDownList($model, 'state', $consulta->getHumanStates());?>
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
/*
			'settings' => array(
				'height' => '800px',
			),
*/
		    'htmlOptions' => array(
		        'rows' => 20,
		        'cols' => 80,
		    ),
		));
		?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Publicar' : 'Actualitzar');
		$cancelURL='/consulta/teamView/'.$consulta->id;
		?>
		<input type="button" value="Cancelar" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />

	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<h1>La consulta</h1>
<div class="view" style="padding:4px;">
<?php echo $this->renderPartial('//consulta/_teamView', array('model'=>$consulta)); ?>

</div>

