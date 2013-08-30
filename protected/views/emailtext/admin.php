<?php
/* @var $this EmailtextController */
/* @var $model Emailtext */
?>

<h1><?php echo __('Manage default email texts');?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->theme->baseUrl.'/css/pgridview.css',
	'id'=>'text-grid',
	'selectableRows'=>1,
	'selectionChanged'=>'function(id){ location.href = "'.$this->createUrl('/emailtext/update').'/"+$.fn.yiiGridView.getSelection(id);}',
	'template' => '{items}',
	'dataProvider'=>$model->search(),
	'ajaxUpdate'=>true,
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
			array(
				'header'=>__('State'),
				'type' => 'raw',
				'value'=>'Enquiry::model()->getHumanStates($data[\'state\'])',
			),
))); ?>

