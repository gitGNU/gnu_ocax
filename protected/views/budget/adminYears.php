<?php
/* @var $this BudgetController */
/* @var $model Budget */

$this->menu=array(
	array('label'=>'Create Year', 'url'=>array('createYear')),
);

?>

<h1>Manage years</h1>

<?php
$this->widget('PGridView', array(
	'id'=>'budget-grid',
	'dataProvider'=>$years,
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/budget/updateYear',
    ),
	'ajaxUpdate'=>true,
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
		'year',
		//'concept',
		'provision',
		'spent',
		array(
			'header'=>'Published',
			'name'=>'code',
			'value'=>'$data[\'code\']',
		),
		array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
?>

