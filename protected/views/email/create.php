<?php
/* @var $this EmailController */
/* @var $model Email */
/* @var $form CActiveForm */

if($returnURL == 'consulta/teamView'){
	$this->menu=array(
		array('label'=>'Ver Consulta', 'url'=>array('/consulta/teamView', 'id'=>$consulta->id)),
		array('label'=>'Actualizar estat', 'url'=>array('/consulta/update', 'id'=>$consulta->id)),
		array('label'=>'Editar Consulta', 'url'=>array('/consulta/edit', 'id'=>$consulta->id)),
		array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$consulta->id, 'menu'=>'team')),
		array('label'=>'Listar consultas', 'url'=>array('/consulta/managed')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
}
if($returnURL == 'consulta/adminView'){
	$this->menu=array(
		array('label'=>'Ver Consulta', 'url'=>array('/consulta/adminView', 'id'=>$consulta->id)),
		//array('label'=>'Actualizar estat', 'url'=>array('/consulta/update', 'id'=>$consulta->id)),
		//array('label'=>'Editar Consulta', 'url'=>array('/consulta/edit', 'id'=>$consulta->id)),
		array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$consulta->id, 'menu'=>'manager')),
		array('label'=>'Listar consultas', 'url'=>array('/consulta/admin')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
}
?>

<style>
#recipients_link{
	cursor:pointer;
	text-decoration:underline;
}
.form{
	-webkit-border-radius:10px;
	border-radius:10px;
	background-color:#D9CCB9;
	padding:10px;
	margin-bottom:10px;

}
</style>

<script>
function toggleRecipients(){
	if ($('#recipients').is (':visible'))
		$('#recipients_link').html('Show');
	else
		$('#recipients_link').html('Hide');
	$('#recipients').toggle();
}
</script>


<h1>Enviar correo</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'email-form',
	//'enableAjaxValidation'=>true,
	//'enableClientValidation'=>false,
	'action'=>Yii::app()->baseUrl.'/email/create',
)); ?>

	<?php echo $form->hiddenField($model,'consulta'); ?>
	<input type="hidden" name="Email[returnURL]" value="<?php echo $returnURL;?>" />

	<div class="row">
		<?php

		$sender=User::model()->findByPk($model->sender);
		$senderList=array(	0=>Config::model()->findByPk('noreply')->value,
							$sender->id=>$sender->email);
		$model->sender=0;
		?>
		<?php echo $form->labelEx($model,'sender'); ?>
		<?php echo $form->dropDownList($model, 'sender', $senderList );?>
		<?php echo $form->error($model,'sender'); ?>
	</div>


	<div class="row">
		<?php /*echo $form->labelEx($model,'recipients');*/ ?>
		<?php
			$criteria = array(
				'with'=>array('consultaSubscribes'),
				'condition'=>' consultaSubscribes.consulta = '.$consulta->id,
				'together'=>true,
			);
			$subscribedUsers = User::model()->findAll($criteria);
			$model->recipients='';
			foreach($subscribedUsers as $subscribed)
				$model->recipients=$model->recipients.' '.$subscribed->email.',';
			$model->recipients = substr_replace($model->recipients ,"",-1);
			echo $form->hiddenField($model,'recipients');
			
			echo '<p><b>'.count($subscribedUsers).' Recipients</b> <span id="recipients_link" onClick="js:toggleRecipients();">Show</span>';
			echo '<div id="recipients" style="background-color:white;padding:4px;display:none">'.$model->recipients.'</div>';
		?>
		</p>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
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

		    'htmlOptions' => array(
		        'rows' => 6,
		        'cols' => 80,
		    ),
		));
		?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Enviar' : 'Save'); ?>
		<input type="button" value="Cancel" onclick='js:window.location="<?php echo Yii::app()->baseUrl.'/'.$returnURL.'/'.$consulta->id;?>";' />

	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<h1>La consulta</h1>
<div class="view" style="padding:4px">
<?php echo $this->renderPartial('//consulta/_teamView', array('model'=>$consulta,'respuestas'=>$respuestas)); ?>
</div>

