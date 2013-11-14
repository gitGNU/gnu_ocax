<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

?>

<?php
$yearBudget=Budget::model()->findByAttributes(array('parent'=>Null,'year'=>$model->year));
$this->menu=array(
	array('label'=>__('Edit').' '.$model->year, 'url'=>array('//budget/updateYear/'.$yearBudget->id)),
	array('label'=>__('List Years'), 'url'=>array('//budget/adminYears')),
);
$this->inlineHelp=':csv_format';
?>

<style>
p { font-size:1.3em; }
.error { margin-left:10px; color:red; }
.warn { margin-left:10px; color:#CD661D; }
.success { margin-left:10px; color:green; }
</style>

<script>
function changeYear(el){
	$('#ImportCSV_year').val( $(el).val() );
}
function step3_1_to_4(){
	$('#step_3_1').hide();
	$('#step_4').show();
}
function checkFormat(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/csv/checkCSVFormat',
		type: 'GET',
		dataType: 'json',
		data: { 'csv_file': '<?php echo $model->csv;?>' },
		//beforeSend: function(){  },
		//complete: function(){  },
		success: function(data){
					if(data.error){
						$('#check_format_button').replaceWith('<span class="error">'+data.error+'</span>');
					}else{
						$('#check_format_button').replaceWith('<span class="success">'+data+' registers seem ok</span>');
						$('#step_3').show();
					}
		},
		error: function() { alert("error on checkFormat"); },
	});
}
function checkTotals(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/csv/checkCSVTotals',
		type: 'GET',
		dataType: 'json',
		data: { 'csv_file': '<?php echo $model->csv;?>' },
		//beforeSend: function(){  },
		//complete: function(){  },
		success: function(data){
					if(data.error){
						alert(data.error);
					}else if(data.totals){
						$('#check_totals_button').replaceWith('<span class="warn">Some totals do not match'+data.totals+'</span>');
						$('#step_3_1').show();
					}else{
						$('#check_totals_button').replaceWith('<span class="success">'+data+' registers seem ok</span>');
						$('#step_4').show();
					}
		},
		error: function() { alert("error on checkTotals"); },
	});
}
function dumpBudgets(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/dumpBudgets',
		type: 'GET',
		dataType: 'json',
		//beforeSend: function(){  },
		//complete: function(){  },
		success: function(data){
					if(data == 1){
						$('#dump_button').replaceWith('<span class="error">Failed to back up Budgets. See your Admin.</span>');
					}else{
						$('#dump_button').replaceWith('<span class="success">All budgets backed up ok.</span>');
						$('#step_5').show();
					}
		},
		error: function() { alert("error on dump Budgets"); },
	});
}
function importData(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/csv/importCSVData/<?php echo $model->year;?>',
		type: 'GET',
		dataType: 'json',
		data: { 'csv_file': '<?php echo $model->csv;?>'	},
		beforeSend: function(){ $("#import_button").attr("disabled", "disabled"); $('#loading_importing_csv').show(); },
		complete: function(){ $('#loading_importing_csv').hide(); },
		success: function(data){
					if(data.error)
						$('#import_button').replaceWith('<span class="error">'+data.error+'</span>');
					else{
						msg = '<span class="success">New registers: '+data.new_budgets+', Updated registers: '+data.updated_budgets+'</span>';
						$('#import_button').replaceWith(msg);
						$('#step_6').show();
					}
		},
		error: function() { alert("error on importData"); },
	});
}
</script>

<?php echo '<h1>'.__('Import csv into').' '.$model->year.'</h1>';?>

<?php
if(!$model->csv){

echo "<script>$(document).ready(function() {\n";
echo "$('#upload-form').submit(function () {\n";
//echo "	$('#ImportCSV_year').val( $('#importYear').val() );\n";

//echo "alert($('#ImportCSV_year').val());";
echo "	return true;";
echo "});\n";
echo "});</script>\n";

echo '<p>Step 1. Upload .csv file</p>';
$form = $this->beginWidget(
    'CActiveForm',
    array(
		'id' => 'upload-form',
		'enableAjaxValidation' => false,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
		'action' => Yii::app()->request->baseUrl.'/csv/uploadCSV/'.$model->year,
    ));
	echo '<p>';
	echo '<div class="row">';
		echo $form->hiddenField($model, 'year');
		echo $form->labelEx($model, 'csv');
		echo $form->fileField($model, 'csv');
		echo $form->error($model, 'csv');
	echo '</div>';
	echo '</p>';
	echo '<p>'.CHtml::submitButton('Upload').'</p>';
$this->endWidget();

}
else{
	echo '<p>Step 1. <span class="success">File up loaded correctly</span></p>';
}
if($model->step == 2)
	echo '<p id="step_2">Step 2. Check file format <input id="check_format_button" type="button" value="Check" onClick="js:checkFormat();" /></p>';


echo '<p id="step_3" style="display:none">Step 3. Check totals ';
echo '<input id="check_totals_button" type="button" value="Check" onClick="js:checkTotals();" /></p>';

echo '<p id="step_3_1" style="display:none">';
echo '<input type="button" value="Try again" onClick="js:location.href=\''.Yii::app()->request->baseUrl.'/csv/importCSV/'.$model->year.'\';" /> ';
echo '<input type="button" value="Continue anyway" onClick="js:step3_1_to_4();" /></p>';

echo '<p id="step_4" style="display:none">Step 4. Backup budget database: ';
echo '<input id="dump_button" type="button" style="margin-left:15px;" value="Backup" onClick="js:dumpBudgets();" /></p>';

echo '<p id="step_5" style="display:none">Step 5. Import into database: <b>'.$model->year.'</b> ';
echo '<input id="import_button" type="button" style="margin-left:15px;" value="Import" onClick="js:importData();" />';
echo '<img id="loading_importing_csv" style="display:none" src="'.Yii::app()->request->baseUrl.'/images/loading.gif" />';
echo '</p>';

$criteria=new CDbCriteria;
$criteria->condition='parent IS NULL AND year = '.$model->year;
$year=Budget::model()->find($criteria);

echo '<p id="step_6" style="display:none">Return to year '.CHtml::link($model->year, array('budget/updateYear', 'id'=>$year->id)).'</p>';

?>


