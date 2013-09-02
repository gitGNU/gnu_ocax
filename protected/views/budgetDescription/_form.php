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

	<div class="row">
		<?php echo $form->labelEx($model,'csv_id'); ?>
		<?php echo $form->textField($model,'csv_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'csv_id'); ?>
	</div>

	<?php
		if($listData = getLanguagesArray()){
			echo '<div class="row">';
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

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

<?php }else{ ?>
	<p>
	<?php $this->widget('zii.widgets.CDetailView', array(
		'cssFile' => Yii::app()->theme->baseUrl.'/css/pdetailview.css',
		'data'=>$model,
		'attributes'=>array(
			'csv_id',
			'language',
			'code',
		),
	)); ?>
	</p>
<?php } ?>

	<div class="row">
		<?php echo $form->labelEx($model,'label'); ?>
		<?php echo $form->textField($model,'label',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'common'); ?>
		<div class="hint"><?php echo __('Common to all councils');?></div>
		<?php echo $form->checkBox($model,'common', array('checked'=>$model->common)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'concept'); ?>
		<?php echo $form->textField($model,'concept',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'concept'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
<?php
$this->widget('ext.tinymce.TinyMce', array(
    'model' => $model,
    'attribute' => 'description',
    'compressorRoute' => 'tinyMce/compressor',
    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',
    'settings' => array(
    	'entity_encoding' => "raw",
	),
    'htmlOptions' => array(
        'rows' => 10,
        'cols' => 80,
    ),
));
?>
		<?php echo $form->error($model,'description'); ?>
	</div>



	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
