<?php
/* @var $this EmailtextController */
/* @var $model Emailtext */

$this->menu=array(
	array('label'=>'Change text', 'url'=>array('update', 'id'=>$model->state)),
	array('label'=>'Manage texts', 'url'=>array('admin')),
);
?>

<div class="enquiry">
<div class="title">Text for state "<?php echo Enquiry::model()->getHumanStates($model->state); ?>"</div>


<?php echo $model->getBody();?>
</div>

