<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

$this->menu=array(
	array('label'=>'View enquiry', 'url'=>array('/enquiry/teamView', 'id'=>$model->id)),
	array('label'=>'Add reply', 'url'=>array('/reply/create?enquiry='.$model->id)),
	array('label'=>'Edit enquiry', 'url'=>array('/enquiry/edit', 'id'=>$model->id)),
	array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
	array('label'=>'List enquirys', 'url'=>array('/enquiry/managed')),

	//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
);
$this->contextHelp='After changing the state you can email subscribed users to inform them of the change';
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enquiry-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="form">

	<div class="title">Change state</div>

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php
			$dropDown_data = $model->getHumanStates();
			unset($dropDown_data[0]);
		?>
		<?php echo $form->dropDownList($model, 'state', $dropDown_data);?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Update');
		$cancelURL='/enquiry/teamView/'.$model->id;
		?>
		<input type="button" value="Cancel" onclick="js:window.location='<?php echo Yii::app()->request->baseUrl?><?php echo $cancelURL?>';" />

	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<p></p>
<?php echo $this->renderPartial('_teamView', array('model'=>$model)); ?>








