<?php
/* @var $this FileController */
/* @var $model File */
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

$dataProvider = new CActiveDataProvider('File', array(
    'criteria'=>array('condition'=>'model = "CmsPage"')
));

$this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->theme->baseUrl.'/css/pgridview.css',
	'id'=>'file-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
			'header'=>'web path',
			'name'=>'path',
			'type'=>'raw',
			'value'=>'$data->getWebPath()',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
		),
	),
));

?>
