<?php
/* @var $this CmspageController */
/* @var $model CmsPage */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cms-page-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title"><?php echo $title;?></div>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'block'); ?>
		<?php echo $form->textField($model,'block'); ?>
		<?php echo $form->error($model,'block'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'weight'); ?>
		<?php echo $form->textField($model,'weight'); ?>
		<?php echo $form->error($model,'weight'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pagename'); ?>
		<?php echo $form->textField($model,'pagename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'pagename'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'pageTitle'); ?>
		<?php echo $form->textField($model,'pageTitle',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'pageTitle'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php /* echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); */ ?>
<?php
$this->widget('ext.tinymce.TinyMce', array(
    'model' => $model,
    'attribute' => 'body',
    // Optional config
    'compressorRoute' => 'tinyMce/compressor',
    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',
	'settings' => array(
		'theme_advanced_buttons1' => "	bold,italic,underline,strikethrough,|,fontsizeselect,|,justifyleft,justifycenter,
										justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,
										undo,redo,|,link,unlink,|,code",
	),
    'htmlOptions' => array(
        'rows' => 6,
        'cols' => 80,
    ),
));
?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'published'); ?>
		<?php echo $form->checkBox($model,'published', array('checked'=>$model->published)); ?>
		Is public
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'heading'); ?>
		<?php echo $form->textField($model,'heading',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'heading'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'metaTitle'); ?>
		<?php echo $form->textField($model,'metaTitle',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'metaTitle'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'metaDescription'); ?>
		<?php echo $form->textField($model,'metaDescription',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'metaDescription'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'metaKeywords'); ?>
		<?php echo $form->textField($model,'metaKeywords',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'metaKeywords'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
