<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */
if(Yii::app()->user->getUserID() == $model->team_member){
	$this->menu=array(
		array('label'=>__('View Enquiry'), 'url'=>array('/enquiry/teamView', 'id'=>$model->id)),
		array('label'=>__('Change type'), 'url'=>array('/enquiry/changeType', 'id'=>$model->id)),
		array('label'=>__('Update state'), 'url'=>array('/enquiry/update', 'id'=>$model->id)),
		array('label'=>__('Add reply'), 'url'=>array('/reply/create?enquiry='.$model->id)),
		array('label'=>__('Sent emails'), 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
		array('label'=>__('List enquiries'), 'url'=>array('/enquiry/managed')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
	);
}
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));

if($replys || Yii::app()->user->getUserID() == $model->team_member)
	echo '<div class="view" style="padding:4px">';
?>

<?php if(Yii::app()->user->getUserID() == $model->team_member){
	$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>'Submitted por',
	        'value'=>$model->user0->fullname.' '.__('on the').' '.$model->created,
		),
		array(
	        'label'=>'Asignada a',
	        'value'=>($model->team_member) ? $model->teamMember->fullname.' '.__('on the').' '.$model->assigned : "",
		),
		array(
	        'label'=>__('Type'),
	        'value'=>$model->humanTypeValues[$model->type],
		),
		'capitulo',
		array(
	        'label'=>__('State'),
	        'value'=>$model->getHumanStates($model->state),
		),
	),
	));
}?>

<?php

foreach($replys as $reply){
	echo '<hr>';
	echo '<p>';
	echo '<b>Reply: '.date_format(date_create($reply->created), 'Y-m-d').'</b><br />';
	echo '<p>'.$reply->body.'</p>';
	echo '</p>';
}

if($replys || Yii::app()->user->getUserID() == $model->team_member)
	echo '</div>';
?>


