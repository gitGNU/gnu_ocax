<?php
/* @var $this ReplyController */
/* @var $model Reply */
/* @var $form CActiveForm */

$this->menu=array(
	array('label'=>'Ver Enquiry', 'url'=>array('/enquiry/teamView', 'id'=>$enquiry->id)),
	array('label'=>'Actualizar estat', 'url'=>array('/enquiry/update', 'id'=>$enquiry->id)),
	array('label'=>'Editar Enquiry', 'url'=>array('/enquiry/edit', 'id'=>$enquiry->id)),
	array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$enquiry->id, 'menu'=>'team')),
	array('label'=>'Listar enquirys', 'url'=>array('/enquiry/managed')),
	//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);

$this->contextHelp='This Reply will be published on the website.<br /><br />After publishing you can:<br /><br />
					a) attach files and<br />
					b) send an email to subscribed users informing them of this update.';
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	//'id'=>'reply-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>false,
)); ?>

	<div class="title"><?php echo __('Add reply')?></div>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->hiddenField($model,'enquiry');?>

	<div class="row">
		<?php echo $form->label($enquiry,'state');?>
		<?php $model->state=$enquiry->state;?>
		<?php
			$dropDown_data = Enquiry::model()->getHumanStates();
			unset($dropDown_data[0]);
		?>
		<?php echo $form->dropDownList($model, 'state', $dropDown_data);?>
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


