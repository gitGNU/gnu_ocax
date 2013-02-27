<?php
/* @var $this EmailtextController */
/* @var $model Emailtext */

$this->breadcrumbs=array(
	'Emailtexts'=>array('index'),
	'Manage',
);
/*
$this->menu=array(
	array('label'=>'List Emailtext', 'url'=>array('index')),
	array('label'=>'Create Emailtext', 'url'=>array('create')),
);
*/
?>

<h1>Manage default email texts</h1>

<?php $this->widget('PGridView', array(
	'id'=>'consulta-grid',
	'dataProvider'=>$model->search(),
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/emailtext/update',
    ),
	'ajaxUpdate'=>true,
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
			array(
				'header'=>'State',
				//'name'=>'state',
				'type' => 'raw',
				'value'=>'Consulta::model()->getHumanStates($data[\'state\'])',
			),
            array('class'=>'PHiddenColumn','value'=>'"$data[state]"'),
))); ?>
