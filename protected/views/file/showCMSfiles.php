<?php
/* @var $this FileController */
/* @var $model File */
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

$dataProvider = new CActiveDataProvider('File', array(
    'criteria'=>array('condition'=>'model = "CmsPage"')
));

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'file-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'webPath',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
		),
	),
));

?>
