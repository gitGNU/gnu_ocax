<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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
if($model->csv){
	$importAgain = array( array('label'=>__('Upload CSV again'), 'url'=>array('csv/importCSV/'.$model->year)), );
	array_splice( $this->menu, 1, 0, $importAgain );
}

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
function step6_1_to_7(){
	$('#step_6_1').hide();
	$('#step_7').show();
}
function checkEncoding(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/csv/checkEncoding',
		type: 'GET',
		//dataType: 'json',
		data: { 'csv_file': '<?php echo $model->csv;?>' },
		//beforeSend: function(){  },
		//complete: function(){  },
		success: function(data){
					if(data.error){
						$('#check_encoding_button').replaceWith('<span class="error">'+data.error+'</span>');
					}else{
						$('#check_encoding_button').replaceWith('<span class="success">'+data+'</span>');
						$('#step_3').show();
					}
		},
		error: function() { alert("error on checkEncoding"); },
	});
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
						$('#step_4').show();
					}
		},
		error: function() { alert("error on checkFormat"); },
	});
}
function checkOrder(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/csv/checkCSVOrder',
		type: 'GET',
		dataType: 'json',
		data: { 'csv_file': '<?php echo $model->csv;?>' },
		//beforeSend: function(){  },
		//complete: function(){  },
		success: function(data){
					if(data.error){
						$('#check_order_button').replaceWith('<span class="error">'+data.error+'</span>');
					}else{
						$('#check_order_button').replaceWith('<span class="success">'+data+'</span>');
						$('#step_5').show();
					}
		},
		error: function() { alert("error on checkOrder"); },
	});
}
function checkHierarchy(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/csv/checkHierarchy',
		type: 'GET',
		dataType: 'json',
		data: { 'csv_file': '<?php echo $model->csv;?>' },
		//beforeSend: function(){  },
		//complete: function(){  },
		success: function(data){
					if(data.error){
						$('#check_hierarchy_button').replaceWith('<span class="error">'+data.error+'</span>');
					}else{
						$('#check_hierarchy_button').replaceWith('<span class="success">'+data.msg+'</span>');
						if(data.created > 0){
							$('#step_newcsv_6').show();
						}else{
							$('#step_6').show();
						}
					}
		},
		error: function() { alert("error on checkHierarchy"); },
	});
}
function addMissingTotals(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/csv/addMissingTotals',
		type: 'GET',
		dataType: 'json',
		data: { 'csv_file': '<?php echo $model->csv;?>' },
		//beforeSend: function(){  },
		//complete: function(){  },
		success: function(data){
					if(data.error){
						$('#add_missing_totals_button').replaceWith('<span class="error">'+data.error+'</span>');
					}else{
						$('#add_missing_totals_button').replaceWith('<span class="success">'+data+'</span>');
						$('#step_newcsv_7').show();
					}
		},
		error: function() { alert("addMissingTotals"); },
	});
}
function addMissingDescriptions(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/csv/addMissingDescriptions',
		type: 'GET',
		dataType: 'json',
		data: { 'csv_file': '<?php echo $model->csv;?>' },
		//beforeSend: function(){  },
		//complete: function(){  },
		success: function(data){
					if(data.error){
						$('#add_missing_descriptions_button').replaceWith('<span class="error">'+data.error+'</span>');
					}else{
						download = '<a href="'+data+'">'+data+'</a>';
						$('#add_missing_descriptions_button').replaceWith('<span class="success">Download new CSV:</span> '+download);
					}
		},
		error: function() { alert("error on addMissingDescriptions"); },
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
						$('#step_6_1').show();
					}else{
						$('#check_totals_button').replaceWith('<span class="success">'+data+' registers seem ok</span>');
						$('#step_7').show();
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
						$('#step_8').show();
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
						$('#step_9').show();
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

}else{
	echo '<p>Step 1. <span class="success">File uploaded correctly</span></p>';
}

if($model->step == 2){
	echo '<p id="step_2">Step 2. Check file for UTF-8 encoding ';
	echo '<input id="check_encoding_button" type="button" value="Check" onClick="js:checkEncoding();" /></p>';
}

echo '<p id="step_3" style="display:none">Step 3. Check file format ';
echo '<input id="check_format_button" type="button" value="Check" onClick="js:checkFormat();" /></p>';

echo '<p id="step_4" style="display:none">Step 4. Check numerical order of internal codes  ';
echo '<input id="check_order_button" type="button" value="Order" onClick="js:checkOrder();" /></p>';

echo '<p id="step_5" style="display:none">Step 5. Check internal code hierarchy ';
echo '<input id="check_hierarchy_button" type="button" value="Check" onClick="js:checkHierarchy();" /></p>';

echo '<p id="step_newcsv_6" style="display:none">Step 6. Calculate totals for missing registers ';
echo '<input id="add_missing_totals_button" type="button" value="Calculate" onClick="js:addMissingTotals();" /></p>';

echo '<p id="step_newcsv_7" style="display:none">Step 7. Add missing codes and concepts ';
echo '<input id="add_missing_descriptions_button" type="button" value="Complete" onClick="js:addMissingDescriptions();" /></p>';

echo '<p id="step_6" style="display:none">Step 6. Check totals ';
echo '<input id="check_totals_button" type="button" value="Check" onClick="js:checkTotals();" /></p>';

echo '<p id="step_6_1" style="display:none">';
echo '<input type="button" value="Try again" onClick="js:location.href=\''.Yii::app()->request->baseUrl.'/csv/importCSV/'.$model->year.'\';" /> ';
echo '<input type="button" value="Continue anyway" onClick="js:step6_1_to_7();" /></p>';

echo '<p id="step_7" style="display:none">Step 7. Backup budget database: ';
echo '<input id="dump_button" type="button" style="margin-left:15px;" value="Backup" onClick="js:dumpBudgets();" /></p>';

echo '<p id="step_8" style="display:none">Step 8. Import into database: <b>'.$model->year.'</b> ';
echo '<input id="import_button" type="button" style="margin-left:15px;" value="Import" onClick="js:importData();" />';
echo '<img id="loading_importing_csv" style="display:none" src="'.Yii::app()->request->baseUrl.'/images/loading.gif" />';
echo '</p>';

$criteria=new CDbCriteria;
$criteria->condition='parent IS NULL AND year = '.$model->year;
$year=Budget::model()->find($criteria);

echo '<p id="step_9" style="display:none">Return to year '.CHtml::link($model->year, array('budget/updateYear', 'id'=>$year->id)).'</p>';

?>


