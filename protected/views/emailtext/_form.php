<?php
/* @var $this EmailtextController */
/* @var $model Emailtext */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'emailtext-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->hiddenField($model,'state'); ?>
	<div class="row">
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
		        'rows' => 6,
		        'cols' => 80,
		    ),
		));
		?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Update'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
