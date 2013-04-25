<?php
/* @var $this BulkEmailController */
/* @var $model BulkEmail */

$this->menu=array(
	array('label'=>__('Create bulk email'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#bulk-email-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo __('Manage Bulk Emails');?></h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'bulk-email-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'created',
		'subject',
		array(
			'header'=>__('State'),
			'name'=>'sent',
			'type' => 'raw',
			'value'=>'$data->getHumanSentValues($data[\'sent\'])',
		),
		//'sent',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'buttons'=>array(
				'update' => array(
					'label'=>'View',
		            'url'=>'Yii::app()->createUrl("bulkEmail/view", array("id"=>$data->id))',
				),
			),
		),
	),
)); ?>
