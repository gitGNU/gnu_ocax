<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

$this->menu=array(
	array('label'=>__('View enquiry'), 'url'=>array('/enquiry/teamView', 'id'=>$model->id)),
	array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
	array('label'=>__('List enquiries'), 'url'=>array('/enquiry/managed')),
);

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="form">

	<div class="title"><?php echo __('Assess reply')?></div>

	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php
			$dropDown_data = $model->getHumanStates();
			unset($dropDown_data[1]);
			unset($dropDown_data[2]);
			unset($dropDown_data[3]);
			unset($dropDown_data[4]);
		?>
		<?php echo $form->dropDownList($model, 'state', $dropDown_data);?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(__('Assess'));
		$cancelURL='/enquiry/teamView/'.$model->id;
		?>
		<input type="button" value="<?php echo __('Cancel')?>" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />

	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<p></p>
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>








