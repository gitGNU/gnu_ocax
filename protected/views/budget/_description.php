<?php

if($description = BudgetDescription::model()->findByAttributes(array('csv_id'=>$model->csv_id, 'language'=>Yii::app()->language))) {
	echo '<h2>'.$description->concept.'</h2>';
	echo '<p>'.$description->description.'</p>';
	
}else{

echo '<h2>No description found!!</h2>';	
	
}

?>
