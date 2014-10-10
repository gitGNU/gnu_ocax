<?php

/**
 * OCAX -- Citizen driven Observatory software
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

/* @var $this CmsPageController */
/* @var $model CmsPage */
/* @var $form CActiveForm */
?>

<style>
.wideItem {float:left; padding-right:80px;}
</style>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cms-page-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php
		if($listData = getLanguagesArray())
			$show_language=$listData[$content->language];
		else
			$show_language='';
	?>

	<div class="title"><?php echo $title;?></div>
	<?php echo CHtml::errorSummary(array($model, $content)); ?>

	<?php
		if(!$model->isNewRecord  && $listData = getLanguagesArray()){
			echo '<div class="row wideItem">';
			echo $form->labelEx($content,'language');
			//echo '<div class="hint">'.__('Translations').'</div>';
			echo $form->dropDownList($content, 'language', $listData,
									array('onchange'=>	'location.href="'.Yii::app()->request->baseUrl.
														'/cmsPage/update/'.$model->id.'?lang="+this.options[this.selectedIndex].value'
									));
			echo '</div>';
		}
	?>


	<div class="row wideItem">
		<?php echo $form->labelEx($model,'block'); ?>
		<?php echo $form->textField($model,'block',array('size'=>5)); ?>
		<?php echo $form->error($model,'block'); ?>
	</div>

	<div class="row wideItem">
		<?php echo $form->labelEx($model,'weight'); ?>
		<?php echo $form->textField($model,'weight',array('size'=>5)); ?>
		<?php echo $form->error($model,'weight'); ?>
	</div>

	<div class="row wideItem">
		<?php echo $form->labelEx($model,'published'); ?>
		<?php echo $form->checkBox($model,'published', array('checked'=>$model->published)); ?>
	</div>

	<div style="clear:both"></div>

	<div class="horizontalRule"></div>
	<div class="sub_title"><?php echo __('Content').' '.$show_language;?></div>

	<div class="row">
		<?php echo $form->labelEx($content,'pageTitle'); ?>
		<?php echo $form->textField($content,'pageTitle',array('style'=>'width:450px')); ?>
		<?php echo $form->error($content,'pageTitle'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($content,'pageURL'); ?>
		<?php echo Yii::app()->createAbsoluteUrl('/p').'/ '.
					$form->textField($content,'pageURL',array('style'=>'width:300px')); ?>
		<?php echo $form->error($content,'pageURL'); ?>
	</div>

	<div class="row">
		<?php
			echo $form->labelEx($content,'body');
			if(!Config::model()->findByPk('HTMLeditorSafe')->value){
				echo '<label>'.__('Warning! Safe HTML editing is off. Copy/paste can create problems').'.</label>';
				$htmlButton = ",|,code";
				$valid_elements = "*[*]";
				//http://www.tinymce.com/wiki.php/configuration:valid_children
				$valid_children = "+body[style]";
			}else{
				$htmlButton = "";
				$valid_elements = "h1,h2,h3,@[style],p,span,a[href|target=_blank],strong/b,div[align],br,ul,ol,li,img[src]";
				$valid_children = "";
			}
		?>

<?php
$settings = array('theme_advanced_buttons1' => "undo,redo,|,bold,italic,underline,strikethrough,|,formatselect,|,
												justifyleft,justifycenter,justifyright,|,
												bullist,numlist,|,outdent,indent,|,
												link,unlink,|,image".$htmlButton,
					'convert_urls'=>true,
					'relative_urls'=>false,
					'remove_script_host'=>false,
					'theme_advanced_resize_horizontal' => 0,
					'theme_advanced_resize_vertical' => 0,
					'theme_advanced_resizing_use_cookie' => false,
					'width'=>'100%',
					'valid_elements' => $valid_elements,
					'valid_children' => $valid_children,
				);

if(Config::model()->findByPk('HTMLeditorUseCompressor'))
	$settings['useCompression']=true;
else
	$settings['useCompression']=false;

$init =  array(
    'model' => $content,
    'attribute' => 'body',
    // Optional config
    'compressorRoute' => 'tinyMce/compressor',
    //'spellcheckerUrl' => array('tinyMce/spellchecker'),
    // or use yandex spell: http://api.yandex.ru/speller/doc/dg/tasks/how-to-spellcheck-tinymce.xml
    'spellcheckerUrl' => 'http://speller.yandex.net/services/tinyspell',
	'settings' => $settings,
);

if(!Config::model()->findByPk('HTMLeditorUseCompressor')->value)
	unset($init['compressorRoute']);

$this->widget('ext.tinymce.TinyMce', $init);
echo $form->error($content,'body');

?>

	</div>

<?php if(1 == 0){ ?>
	<div class="row">
		<?php echo $form->labelEx($content,'heading'); ?>
		<?php echo $form->textField($content,'heading',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($content,'heading'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($content,'metaTitle'); ?>
		<?php echo $form->textField($content,'metaTitle',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($content,'metaTitle'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($content,'metaDescription'); ?>
		<?php echo $form->textField($content,'metaDescription',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($content,'metaDescription'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($content,'metaKeywords'); ?>
		<?php echo $form->textField($content,'metaKeywords',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($content,'metaKeywords'); ?>
	</div>
<?php } ?>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? __('Create') : __('Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
