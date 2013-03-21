<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */
if(Yii::app()->user->getUserID() == $model->team_member){
	$this->menu=array(
		array('label'=>'Ver Enquiry', 'url'=>array('/enquiry/teamView', 'id'=>$model->id)),
		array('label'=>'Actualizar estat', 'url'=>array('/enquiry/update', 'id'=>$model->id)),
		array('label'=>'Anadir reply', 'url'=>array('/reply/create?enquiry='.$model->id)),
		array('label'=>'Emails enviados', 'url'=>array('/email/index/', 'id'=>$model->id, 'menu'=>'team')),
		array('label'=>'Listar enquirys', 'url'=>array('/enquiry/managed')),
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
	        'value'=>$model->user0->fullname.' el día '.$model->created,
		),
		array(
	        'label'=>'Asignada a',
	        'value'=>($model->team_member) ? $model->teamMember->fullname.' on the '.$model->assigned : "",
		),
		array(
	        'label'=>'Tipo',
	        'value'=>$model->humanTypeValues[$model->type],
		),
		'capitulo',
		array(
	        'label'=>'Estat',
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


