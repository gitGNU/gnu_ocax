<?php
/* @var $this ReplyController */
/* @var $model Reply */
/* @var $form CActiveForm */

$this->menu=array(
	array('label'=>__('View enquiry'), 'url'=>array('/enquiry/teamView', 'id'=>$enquiry->id)),
	array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$enquiry->id, 'menu'=>'team')),
	array('label'=>__('List enquiries'), 'url'=>array('/enquiry/managed')),
);

$this->contextHelp='This Reply will be published on the website.<br /><br />After publishing you can:<br /><br />
					a) attach files and<br />
					b) send an email to subscribed users informing them of this update.';
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reply-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>false,
)); ?>

	<div class="title"><?php echo __('Add reply')?></div>

	<?php echo $form->hiddenField($model,'enquiry');?>

	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<div class="hint"><?php echo __('Date the Administration replied');?></div>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'model' => $model,
					'name'=>'Reply[created]',
					'value'=>$model->created,
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'yy-mm-dd',
					),
					'htmlOptions'=>array(
						'style'=>'height:20px;',
						'readonly'=>'readonly',
					),
		)); ?>
		<?php echo $form->error($model,'created'); ?>
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
			'settings' => array(
				'theme_advanced_buttons1' => "	bold,italic,underline,strikethrough,|,fontsizeselect,|,justifyleft,justifycenter,
												justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,
												undo,redo,|,link,unlink",
			),
		    'htmlOptions' => array(
		        'rows' => 20,
		        'cols' => 80,
		    ),
		));
		?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? __('Publish') : __('Update'));
		$cancelURL='/enquiry/teamView/'.$enquiry->id;
		?>
		<input type="button" value="<?php echo __('Cancel')?>" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />

	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<p></p>
<?php echo $this->renderPartial('//enquiry/_teamView', array('model'=>$enquiry)); ?>


