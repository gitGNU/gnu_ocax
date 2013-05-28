<?php


		$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
						array('name'=>'initial_provision', 'type'=>'raw', 'value'=>format_number($model->initial_provision).' €'),
						array('name'=>'trimester_1', 'type'=>'raw', 'value'=>format_number($model->trimester_1).' €'),
						array('name'=>'trimester_2', 'type'=>'raw', 'value'=>format_number($model->trimester_2).' €'),
						array('name'=>'trimester_3', 'type'=>'raw', 'value'=>format_number($model->trimester_3).' €'),
						array('name'=>'trimester_4', 'type'=>'raw', 'value'=>format_number($model->trimester_4).' €'),
					),
		));

?>
