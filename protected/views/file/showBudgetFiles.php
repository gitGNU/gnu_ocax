<?php
/* @var $this FileController */
/* @var $model File */
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<div class="form">
<div class="title">
<?php echo __('Restore all budgets from a backup').' ';?>
<img style="vertical-align:middle;" src="<?php echo Yii::app()->theme->baseUrl?>/images/down.png" />
</div>

<?php echo '<div style="font-size:1.2em;margin-bottom:5px;">'.__('These copies are made right before a CSV file is imported').'</div>';?>

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
	'template' => '{items}{pager}',
	'columns'=>array(
		'name',
		array(
			'class'=>'CButtonColumn',
			'buttons' => array(
				'restore' => array(
					'label'=> __('Restore budgets'),
					'url'=> '"javascript:restoreBudgets(\"".$data->id."\");"',
					'imageUrl' => Yii::app()->theme->baseUrl.'/images/down.png',
					'visible' => 'true',
				)
			),
			'template'=>'{restore} {delete}',
		),
	),
));
?>
</div>

<?php echo __('Note: Delete some backups to save disk space. You only really need the last good copy.');?>
</div>
