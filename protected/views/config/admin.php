<?php
/* @var $this ConfigController */
/* @var $model Config */
?>

<h1><?php echo __('Global parameters');?></h1>
<div style="margin-top:-10px;margin-bottom:-10px;">
<a href="http://ocax.net/?El_software:Admin:Parametros_globales" target="_blank"><?php echo __('more info');?></a>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->theme->baseUrl.'/css/pgridview.css',
	'id'=>'config-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'parameter',
		'value',
		'description',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}',
		),
	),
)); ?>
