<?php
/* @var $this FileController */
/* @var $model File */
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<div class="form">
<div class="title"><?php echo __('Restore Budget Database');?></div>

<div style="font-size:1.4em;text-align:center">Delete all years and restore from a copy</div>

<div style="margin:-10px">
<?php
$dataProvider = new CActiveDataProvider('File', array(
    'criteria'=>array(	'condition'=>'model = "Budget"',
						'order'=>'id DESC',
				),
));
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'file-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'name',
		array(
			'class'=>'CButtonColumn',
			'buttons' => array(
				'restore' => array(
					'label'=> __('Restore budget'),
					'url'=> '"javascript:restoreBudgets(\"".$data->id."\");"',
					'imageUrl' => Yii::app()->theme->baseUrl.'/images/insert_icon.png',
					'visible' => 'true',
				)

			),
			'template'=>'{restore} {delete}',
		),
	),
));

?>
</div>

</div>
