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
 
/* @var $this UserController */
/* @var $model User */

?>
<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}
	
</style>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

<div class="title"><?php echo __('Change your user information')?></div>

<div class="outer">
<div class="left">

	<p style="font-size:1.5em"><?php echo __('Your information')?></p>

	<?php /*echo $form->errorSummary($model);*/ ?>

	<div class="row">
		<?php echo $form->labelEx($model,'fullname'); ?>
		<?php echo $form->textField($model,'fullname',array('size'=>30,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'fullname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>30,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<?php
		if($listData = getLanguagesArray()){
			echo '<div class="row">';
			echo $form->labelEx($model,'language');
			echo '<div class="hint">'.__('Your preferred language').'</div>';
			echo $form->dropDownList($model, 'language', $listData );
			echo '</div>';
		}	
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'is_socio'); ?>
		¿eres socio? <?php echo $form->checkBox($model,'is_socio', array('checked'=>$model->is_socio)); ?>
		¿<a href="#" onClick="js:$('#socio_implica').slideDown('fast');">que implica esto</a>?
		<p id="socio_implica" style="display:none">		
		Ser socio sólo implica apoyar todas y cada una de las enquirys ciudadanas. Es más "simbólico" que práctico legal. Me explico, todas las enquirys/instancias que se envíen en el Ayuntamiento llevan la firma y el NIF del Observatorio Ciudadano, si el Obsevatorio en cuestión tiene 2000 socios, de forma simbólica implica que hay 2000 firmas ciudadanas detrás. De todas formas, legalmente una instancia "vale lo mismo" y tiene el mismo valor si está firmada por 1 o 1000 personas.
		</p>

	</div>

</div>
<div class="right">

<p style="font-size:1.5em"><?php echo __('Change password');?></p>

	<div class="row">
		<?php echo $form->labelEx($model,'new_password'); ?>
		<?php echo $form->passwordField($model,'new_password',array('autocomplete'=>'off'));	?>
		<?php echo $form->error($model,'new_password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password_repeat'); ?>
		<?php echo $form->passwordField($model,'password_repeat',array('autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'password_repeat'); ?>
	</div>

</div>
</div>
<div class="clear"></div>

	<div class="row buttons" style="text-align:center">
		<?php echo CHtml::submitButton(__('Save')); ?>
		<input type="button" onclick="window.location='<?php echo Yii::app()->request->baseUrl;?>/user/panel'" value="<?php echo __('Cancel')?>" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 3500);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('success');?></b></p>
    </div>
<?php endif; ?>
