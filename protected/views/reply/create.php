<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/* @var $this ReplyController */
/* @var $model Reply */
/* @var $form CActiveForm */

$this->menu=array(
	array('label'=>__('View enquiry'), 'url'=>array('/enquiry/teamView', 'id'=>$enquiry->id)),
	array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$enquiry->id, 'menu'=>'team')),
	array('label'=>__('List enquiries'), 'url'=>array('/enquiry/managed')),
);
$this->inlineHelp=':profiles:team_member';
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
		$settings = array('theme_advanced_buttons1' => "bold,italic,underline,strikethrough,|,fontsizeselect,|,justifyleft,justifycenter,
														justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,
														undo,redo,|,link,unlink",
					);
		if(Config::model()->findByPk('HTMLeditorUseCompressor'))
			$settings['useCompression']=true;
		else
			$settings['useCompression']=false;
			
		$this->widget('ext.tinymce.TinyMce', array(
		    'model' => $model,
		    'attribute' => 'body',
		    // Optional config
		    'compressorRoute' => 'tinyMce/compressor',
		    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
		    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
		    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',
			'settings' => $settings,
		    'htmlOptions' => array(
		        'rows' => 6,
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


