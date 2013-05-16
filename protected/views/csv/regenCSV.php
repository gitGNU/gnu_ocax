<?php
/* @var $this FileController */
/* @var $model File */
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.yiigridview.js'] = false;
?>

<div class="form">
<div class="title"><?php echo __('(Re)generate CSV files to include in zip').' ';?></div>

<div style="margin:-10px;">
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'years-grid',
	'dataProvider'=>$dataProvider,
	'template' => '{items}{pager}',
	'columns'=>array(
		'year',
		array(
			'header'=>'Published',
			'name'=>'code',
			'value'=>'$data[\'code\']',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{regen}',
			'buttons' => array(
				'regen' => array(
					'label'=> __('Include budgets'),
					'url'=> '"javascript:regenCSV(\"".$data->year."\");"',
					'imageUrl' => Yii::app()->theme->baseUrl.'/images/regen.png',
					'visible' => 'true',
				)
			),
		),
	),
));
?>
</div>

<div id="loading" style="display:none;text-align:center;">
<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/big_loading.gif" />
</div>
