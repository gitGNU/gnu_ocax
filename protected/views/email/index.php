<?php
/* @var $this EmailController */
/* @var $dataProvider CActiveDataProvider */

if($menu == 'team'){
	$this->menu=array(
		array('label'=>__('View enquiry'), 'url'=>array('/enquiry/teamView', 'id'=>$enquiry->id)),
		array('label'=>__('Update state'), 'url'=>array('/enquiry/update', 'id'=>$enquiry->id)),
		array('label'=>__('Add reply'), 'url'=>array('/reply/create?enquiry='.$enquiry->id)),
		array('label'=>__('Edit enquiry'), 'url'=>array('/enquiry/edit', 'id'=>$enquiry->id)),
		array('label'=>__('List enquiries'), 'url'=>array('/enquiry/managed')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
	);
}
if($menu == 'manager'){
	$this->menu=array(
		array('label'=>__('View enquiry'), 'url'=>array('/enquiry/adminView', 'id'=>$enquiry->id)),
		array('label'=>__('Manage enquiry'), 'url'=>array('/enquiry/manage', 'id'=>$enquiry->id)),
		array('label'=>__('List enquiries'), 'url'=>array('/enquiry/admin')),
		//array('label'=>'email ciudadano', 'url'=>'#', 'linkOptions'=>array('onclick'=>'getEmailForm('.$model->user0->id.')')),
	);
}
?>

<?php echo __('Emails originated from the enquiry');?>
<h1><?php echo $enquiry->title;?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
