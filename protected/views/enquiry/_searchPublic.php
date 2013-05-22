<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */
/* @var $form CActiveForm */
?>

<div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php
			echo $form->textField($model,'body',array('size'=>20,'maxlength'=>255));
			echo ' '.CHtml::submitButton(__('Search'));
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
