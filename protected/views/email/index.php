<?php
/* @var $this EmailController */
/* @var $dataProvider CActiveDataProvider */

if($menu == 'team'){
	$this->menu=array(
		array('label'=>'View enquiry', 'url'=>array('/enquiry/teamView', 'id'=>$enquiry->id)),
		array('label'=>'Update state', 'url'=>array('/enquiry/update', 'id'=>$enquiry->id)),
		array('label'=>'Add reply', 'url'=>array('/reply/create?enquiry='.$enquiry->id)),
		array('label'=>'Edit enquiry', 'url'=>array('/enquiry/edit', 'id'=>$enquiry->id)),
		array('label'=>'List enquirys', 'url'=>array('/enquiry/managed')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
	);
}
if($menu == 'manager'){
	$this->menu=array(
		array('label'=>'View enquiry', 'url'=>array('/enquiry/adminView', 'id'=>$enquiry->id)),
		array('label'=>'Manage enquiry', 'url'=>array('/enquiry/manage', 'id'=>$enquiry->id)),
		array('label'=>'List enquirys', 'url'=>array('/enquiry/admin')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
	);
}
?>

Emails enviados desde la enquiry <h1><?php echo $enquiry->title;?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
