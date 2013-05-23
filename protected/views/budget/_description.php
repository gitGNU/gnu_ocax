<?php

$description = BudgetDescription::model()->findByAttributes(array('csv_id'=>$model->csv_id, 'language'=>Yii::app()->language));
if($description && $description->text != ''){
	echoDescription($description);
	
}else{
	while(1){
		if($model = $model->findByAttributes(array('csv_id'=>$model->csv_parent_id))){
			$description = BudgetDescription::model()->findByAttributes(array('csv_id'=>$model->csv_id, 'language'=>Yii::app()->language));
			if($description && $description->text != ''){
				echoDescription($description);
				break;
			}else
				continue;
		}else{
			echo '<h2>No description found!!</h2>';
			break;
		}
	}
}

function echoDescription($description)
{
	echo '<h2>'.$description->concept.'</h2>';
	echo '<p>'.$description->description.'</p>';	
}

?>
