<?php
/* @var $this BudgetDescriptionController */
/* @var $model BudgetDescription */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'budget-description-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="title"><?php echo $title;?></div>

<?php if($model->isNewRecord){ ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row" style="float:left">
		<?php
			echo $form->labelEx($model,'type');
			echo $form->dropDownList($model, 'type', $model->getHumanTypes(), array('prompt'=>__('Select a type')));
			echo $form->error($model,'type');
		?>
	</div>
	
	<?php
		if($listData = getLanguagesArray()){
			echo '<div class="row" style="float:left;margin-left:40px">';
			echo $form->labelEx($model,'language');
			//echo '<div class="hint">'.__('Description language').'</div>';
			echo $form->dropDownList($model, 'language', $listData, array('prompt'=>__('Select a language')));
			echo $form->error($model,'language');
			echo '</div>';
		}else{
			$model->language='ca';
			echo $form->hiddenField($model,'language');
		}	
	?>

	<div class="row" style="float:left;margin-left:40px">
		<?php
			echo $form->labelEx($model,'code');
			echo $form->textField($model,'code',array('size'=>20,'maxlength'=>20));
			echo $form->error($model,'code');
		?>
	</div>
<?php }else{ ?>

<p>
<div style="margin:-10px">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'code',
		array(
			'name'=>'type',
			'value'=>$model->getHumanTypes($model->type),
		),
		array(
			'name'=>'language',
			'value'=>$model->getHumanLanguages($model->language),
		),
	),
)); ?>
</div>
</p>
<?php } ?>

	<div class="row" style="clear:both">
		<?php
			echo $form->labelEx($model,'concept');
			echo $form->textField($model,'concept',array('size'=>60,'maxlength'=>255));
			echo $form->error($model,'concept');
		?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php /* echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); */ ?>
<?php
$this->widget('ext.tinymce.TinyMce', array(
    'model' => $model,
    'attribute' => 'description',
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
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? __('Create') : __('Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
