<?php
/* @var $this EmailtextController */
/* @var $model Emailtext */

$this->menu=array(
	array('label'=>'Change text', 'url'=>array('update', 'id'=>$model->state)),
	array('label'=>'Manage texts', 'url'=>array('admin')),
);
?>

<h1>Text for state "<?php echo Consulta::model()->getHumanStates($model->state); ?>"</h1>

<div class="view">
<?php echo $model->getBody();?>
</div>
