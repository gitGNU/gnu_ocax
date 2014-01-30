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


// Read this http://www.php.net/manual/en/function.fgetcsv.php
class CsvController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('importCSV','uploadCSV','checkEncoding','checkCSVFormat','checkCSVOrder',
				'checkHierarchy','addMissingTotals','addMissingDescriptions','checkCSVTotals','importCSVData',
				'download','showYears','regenerateCSV','importDescriptions'),
				'expression'=>"Yii::app()->user->isAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionShowYears()
	{
		$dataProvider =new CActiveDataProvider('Budget',array(
			'criteria'=>array('condition'=>'parent IS NULL',
			'order'=>'year DESC'),
		));
		echo $this->renderPartial('regenCSV',array('dataProvider'=>$dataProvider),false,true);
	}

	public function actionRegenerateCSV($id)
	{
		if(ImportCSV::createCSV($id))
			echo 1;
		else
			echo 0;
	}

	public function actionImportCSV($id)
	{
		$model = new ImportCSV;
		$model->year=$id; //$_GET['year'];
		$this->render('importCSV', array('model'=>$model));
	}

	public function actionUploadCSV($id)
	{
		$model = new ImportCSV;
		$model->year = $id;
		if(isset($_POST['ImportCSV']))
		{
			$model->attributes=$_POST['ImportCSV'];

			$model->csv=CUploadedFile::getInstance($model,'csv');
			$filename = $model->year.'-'.Yii::app()->user->id.'.csv';

			$model->csv->saveAs($model->path.$filename);
			$model->csv = $filename;
			$model->step = 2;
		}
		$this->render('importCSV', array('model'=>$model));
	}

	// convert csv to UTF-8
	public function actionCheckEncoding()
	{
		if(!isset($_GET['csv_file'])){
			$error[]='File path not defined.';
			echo CJavaScript::jsonEncode(array('error'=>$error));
			Yii::app()->end();
		}
		$model = new ImportCSV;
		$content = file_get_contents($model->path.$_GET['csv_file']);

		//$original_encoding = mb_detect_encoding($content, 'UTF-8, iso-8859-1, iso-8859-15', true);
		$original_encoding = mb_detect_encoding($content, 'UTF-8', true);		
		if($original_encoding != 'UTF-8'){
			$error[]='This CSV file has not been saved in UTF-8';
			echo CJavaScript::jsonEncode(array('error'=>$error));
			
			//$content = iconv($original_encoding, 'UTF-8', $content);	
			//file_put_contents($model->path.$_GET['csv_file'], $content);
			//echo 'Converted '.$original_encoding.' to UTF-8';
		}else
			echo 'File seems to be UTF-8';
	}


	public function actionCheckCSVFormat()
	{
		$error=array();
		if(isset($_GET['csv_file'])){
			$model = new ImportCSV;
			$lines = file($model->path.$_GET['csv_file']);
			$correct_field_delimiter=0;
			$ids = array();
			foreach ($lines as $line_num => $line) {
				if($line_num==0)
					continue;
				if(!$correct_field_delimiter){
					if (strlen(strstr($line,'|'))>0)
						$correct_field_delimiter=1;
					else{
						$error[]='Delimiter | not found in file.';
						break;
					}
				}
				list($id, $code, $initial_prov, $actual_prov, $t1, $t2, $t3, $t4, $label, $concept) = explode("|", $line);
				$id = trim($id);
				if(trim($concept) == '')
					$error[]='<br />Register '. ($line_num) .': Concept missing';
				if(in_array($id, $ids)) {
					$error[]='<br />Register '. ($line_num) .': Internal code "'.$id.'" is not unique';
				}
				$ids[]=$id;
			}
		}else
			$error[]='File path not defined.';
		if($error)
			echo CJavaScript::jsonEncode(array('error'=>$error));
		else
			echo count($lines) - 1;
	}

	public function actionCheckCSVOrder()
	{
		if(!isset($_GET['csv_file'])){
			echo CJavaScript::jsonEncode(array('error'=>'CSV file path not defined.'));	
			Yii::app()->end();
		}
		$model = new ImportCSV;
		$csv_file = $model->path.$_GET['csv_file'];

		$ordered = $model->csv2array($csv_file);
		ksort($ordered);
				
		$fh = fopen($csv_file, 'w');
		fwrite($fh, $model->getHeader());
		foreach($ordered as $line)
			fwrite($fh, $line);
		fclose($fh);

		echo CJavaScript::jsonEncode("Registers ordered by internal_code");
	}

	public function actionCheckHierarchy()
	{
		if(!isset($_GET['csv_file'])){
			echo CJavaScript::jsonEncode(array('error'=>'CSV file path not defined.'));
			Yii::app()->end();
		}
		$model = new ImportCSV;
		$csv_file = $model->path.$_GET['csv_file'];
		$registers = $model->csv2array($csv_file);
		$newRegisterCnt = 0;
		foreach($registers as $internal_code => & $register){
			if($parent_id = $model->getParentCode($internal_code)){

				if(!array_key_exists($parent_id, $registers)){
					$newRegisterCnt +=1;
					$newRegister = $model->createEmptyBudgetArray();
					$newRegister['internal_code'] = $parent_id;
					$reg = implode ( '|' , $newRegister );
					$registers[$parent_id]=$reg.PHP_EOL;				
				}
			}
		}
		$msg="Hierarchy complete";
		if($newRegisterCnt > 0){
			ksort($registers);
			$fh = fopen($csv_file, 'w');
			fwrite($fh, $model->getHeader());
			foreach($registers as $line)
				fwrite($fh, $line);
			fclose($fh);
			$msg='<span class="warn">'.$newRegisterCnt.' registers missing.</span> Building new CSV file';
		}
		echo CJavaScript::jsonEncode(array('created'=>$newRegisterCnt,'msg'=>$msg));		
	}

	public function actionAddMissingTotals()
	{
		if(!isset($_GET['csv_file'])){
			echo CJavaScript::jsonEncode(array('error'=>'CSV file path not defined.'));	
			Yii::app()->end();
		}
		$model = new ImportCSV;
		$csv_file = $model->path.$_GET['csv_file'];
		$registers = $model->csv2array($csv_file);
		$registers = array_reverse($registers, true);
		
		$budgets = array();
		foreach($registers as $internal_code => $register){
			$budgets[$internal_code] = $model->register2array($register);
		}
		$total=0;
		$parentID_placeholder=Null;
		$budgetID_parent=Null;
		$totals = array();
		
		foreach($budgets as $internal_code => & $budget){
			if(isset($totals[$internal_code])){
				if(!$budget['initial_prov'])
					$budget['initial_prov'] = $totals[$internal_code]['initial_prov'];
				if(!$budget['actual_prov'])
					$budget['actual_prov'] = $totals[$internal_code]['actual_prov'];
				if(!$budget['t1'])
					$budget['t1'] = $totals[$internal_code]['t1'];
				if(!$budget['t2'])
					$budget['t2'] = $totals[$internal_code]['t2'];
				if(!$budget['t3'])
					$budget['t3'] = $totals[$internal_code]['t3'];
				if(!$budget['t4'])
					$budget['t4'] = $totals[$internal_code]['t4'];
			}
			$budgetID_parent = $model->getParentCode($internal_code);
			
			if($budgetID_parent != $parentID_placeholder){
					if(!isset($totals[$budgetID_parent]))
						$totals[$budgetID_parent]=$model->createEmptyBudgetArray();
					$parentID_placeholder=$budgetID_parent;
			}
			$totals[$budgetID_parent]['initial_prov'] += $budget['initial_prov'];
			$totals[$budgetID_parent]['actual_prov'] += $budget['actual_prov'];
			$totals[$budgetID_parent]['t1'] += $budget['t1'];
			$totals[$budgetID_parent]['t2'] += $budget['t2'];
			$totals[$budgetID_parent]['t3'] += $budget['t3'];
			$totals[$budgetID_parent]['t4'] += $budget['t4'];
		}	
		$budgets = array_reverse($budgets, true);

		$fh = fopen($csv_file, 'w');
		fwrite($fh, $model->getHeader());
		foreach($budgets as $budget){
			$line = $model->array2register($budget);
			fwrite($fh, $line.PHP_EOL);	
		}
		fclose($fh);
		echo CJavaScript::jsonEncode("Added missing totals");
	}

	public function actionAddMissingDescriptions()
	{
		if(!isset($_GET['csv_file'])){
			echo CJavaScript::jsonEncode(array('error'=>'CSV file path not defined.'));	
			Yii::app()->end();
		}
		$model = new ImportCSV;
		$csv_file = $model->path.$_GET['csv_file'];
		$registers = $model->csv2array($csv_file);
		$budgets = array();
		$lang=getDefaultLanguage();
		
		foreach($registers as $internal_code => $register){
			$budgets[$internal_code] = $model->register2array($register);

			if(!($budgets[$internal_code]['code'] && $budgets[$internal_code]['concept'])){
				if($description = BudgetDescription::model()->findByPk($lang.$internal_code)){
					if(!$budgets[$internal_code]['code'])
						$budgets[$internal_code]['code'] = $description->code;
					if(!$budgets[$internal_code]['concept'])
						$budgets[$internal_code]['concept'] = $description->concept;
							
				}else
					if(!$budgets[$internal_code]['concept'])
						$budgets[$internal_code]['concept'] = 'UNKNOWN';				
			}
		}
		$fh = fopen($csv_file, 'w');
		fwrite($fh, $model->getHeader());
		foreach($budgets as $budget){
			$line = $model->array2register($budget);
			fwrite($fh, $line.PHP_EOL);	
		}
		fclose($fh);
		echo CJavaScript::jsonEncode(Yii::app()->request->baseUrl.'/files/csv/'.$_GET['csv_file']);
	}


	public function actionCheckCSVTotals()
	{
		if(!isset($_GET['csv_file'])){
			echo CJavaScript::jsonEncode(array('error'=>'CSV file path not defined.'));	
			Yii::app()->end();
		}
		$model = new ImportCSV;
		$csv_file = $model->path.$_GET['csv_file'];
		$registers = $model->csv2array($csv_file);
		
		$lines = file($model->path.$_GET['csv_file']);
		$ids = array();
		foreach ($lines as $line_num => $line) {
			if($line_num==0)
				continue;
			list($id, $code, $initial_prov, $actual_prov, $t1, $t2, $t3, $t4, $label, $concept) = explode("|", $line);
			$id = trim($id);
			$parent_id=$model->getParentCode($id);
			$ids[$id]=array();

			$initial_prov = str_replace('€', '', $initial_prov);
			$initial_prov = (float)trim(str_replace(',', '', $initial_prov));
			$actual_prov = str_replace('€', '', $actual_prov);
			$actual_prov = (float)trim(str_replace(',', '', $actual_prov));

			$ids[$id]['internal_code']=$id;
			$ids[$id]['initial_total']=$initial_prov;
			$ids[$id]['actual_total']=$actual_prov;
			$ids[$id]['children']=array();
			if(array_key_exists($parent_id, $ids)){
				$ids[$parent_id]['children'][$id]=array();
				$ids[$parent_id]['children'][$id]['id']=$id;
				$ids[$parent_id]['children'][$id]['initial_prov']=$initial_prov;
				$ids[$parent_id]['children'][$id]['actual_prov']=$actual_prov;
			}
		}

		//check initial totals
		$initialSummary='';
		foreach($ids as $id){
			if($id['children']){
				$total = 0;
				foreach($id['children'] as $child)
					$total = $total + $child['initial_prov'];
				
				if(bccomp($total, $id['initial_total'])!=0){
					$initialSummary=$initialSummary.'<div style="width:400px;margin-top:15px;">';
					$initialSummary=$initialSummary.'<b>'.$id['internal_code'].' Initial provision is: <span style="float:right;">'.format_number($id['initial_total']).'</span></b>';
					$rowColor='';
					foreach($id['children'] as $child){
						if(!$rowColor){
							$rowColor='style="background-color:#EBEBEB;"';
						}else
							$rowColor='';
						$initialSummary=$initialSummary.'<div '.$rowColor.'>'.$child['id'].'<span style="float:right;">'.format_number($child['initial_prov']).'</span></div>';
					}
					$initialSummary=$initialSummary.'<span style="float:right;text-decoration: underline overline;">Total: '.format_number($total).'</span></div>';
					$initialSummary=$initialSummary.'<div style="clear:both"></div>';
				}
			}
		}

		//check actual totals
		$actualSummary='';
		foreach($ids as $id){
			if($id['children']){
				$total = 0;
				foreach($id['children'] as $child)
					$total = $total + $child['actual_prov'];

				if(bccomp($total, $id['actual_total'])!=0){
					$actualSummary=$actualSummary.'<div style="width:400px;margin-top:15px;">';
					$actualSummary=$actualSummary.'<b>'.$id['internal_code'].' Actual provision is: <span style="float:right;">'.format_number($id['actual_total']).'</span></b>';
					$rowColor='';
					foreach($id['children'] as $child){
						if(!$rowColor){
							$rowColor='style="background-color:#EBEBEB;"';
						}else
							$rowColor='';
						$actualSummary=$actualSummary.'<div '.$rowColor.'>'.$child['id'].'<span style="float:right;">'.format_number($child['actual_prov']).'</span></div>';
					}
					$actualSummary=$actualSummary.'<span style="float:right;text-decoration: underline overline;">Total: '.format_number($total).'</span></div>';
					$actualSummary=$actualSummary.'<div style="clear:both"></div>';
				}
			}
		}

		if($initialSummary || $actualSummary){
			$result = '<div style="margin-top:15px;width:850px;">';

			$result = $result.'<div style="float:left;margin-right:50px;">';
			if($initialSummary)
				$result = $result.$initialSummary;
			else
				$result = $result.'<span style="color:green">Initial provision totals check ok</span>';
			$result = $result.'</div>';

			$result = $result.'<div style="float:right">';
			if($actualSummary)
				$result = $result.$actualSummary;
			else
				$result = $result.'<span style="color:green">Actual provision totals check ok</span>';
			$result = $result.'</div>';

			$result = $result.'<div style="clear:both"></div></div>';
			echo CJavaScript::jsonEncode(array('totals'=>$result));
		}
		else
			echo count($lines) - 1;
	}

	public function actionImportCSVData($id)
	{
		if(! $id ){
			echo CJavaScript::jsonEncode(array('error'=>'Year not selected'));
			Yii::app()->end();
		}
		$criteria=new CDbCriteria;
		$criteria->condition='parent IS NULL AND year='.$id;
		$yearly_budget=Budget::model()->find($criteria);
		if(!$yearly_budget){
			echo CJavaScript::jsonEncode(array('error'=>'Selected year does not exist in database.'));
			Yii::app()->end();
		}
		$error=Null;
		$new_budgets = 0;
		$updated_budgets = 0;
		if(isset($_GET['csv_file'])){
			$model = new ImportCSV;
			$lines = file($model->path.$_GET['csv_file']);
			foreach ($lines as $line_num => $line) {
				if($line_num==0)
					continue;
				list($csv_id, $code, $initial_prov, $actual_prov, $t1, $t2, $t3, $t4, $label, $concept) = explode("|", $line);

				$new_budget=new Budget;
				$new_budget->csv_id = trim($csv_id);
				$new_budget->csv_parent_id = $model->getParentCode($csv_id);
				$new_budget->year = $yearly_budget->year;
				$new_budget->code = trim($code);
				$new_budget->label = trim($label);
				$new_budget->concept = trim($concept);

				$new_budget->initial_provision = trim(str_replace('€', '', $initial_prov));
				$new_budget->initial_provision = trim(str_replace(',', '', $new_budget->initial_provision));
				if(!$new_budget->initial_provision)
					$new_budget->initial_provision = 0;

				$new_budget->actual_provision = trim(str_replace('€', '', $actual_prov));
				$new_budget->actual_provision = trim(str_replace(',', '', $new_budget->actual_provision));
				if(!$new_budget->actual_provision)
					$new_budget->actual_provision = 0;

				$new_budget->trimester_1 = trim(str_replace('€', '', $t1));
				$new_budget->trimester_1 = trim(str_replace(',', '', $new_budget->trimester_1));
				if(!$new_budget->trimester_1)
					$new_budget->trimester_1 = 0;

				$new_budget->trimester_2 = trim(str_replace('€', '', $t2));
				$new_budget->trimester_2 = trim(str_replace(',', '', $new_budget->trimester_2));
				if(!$new_budget->trimester_2)
					$new_budget->trimester_2 = 0;

				$new_budget->trimester_3 = trim(str_replace('€', '', $t3));
				$new_budget->trimester_3 = trim(str_replace(',', '', $new_budget->trimester_3));
				if(eplace(',', '', $new_budget->trimester_3));
				if(!$new_budget->trimester_3)
					$new_budget->trimester_3 = 0;

				$new_budget->trimester_4 = trim(str_replace('€', '', $t4));
				$new_budget->trimester_4 = trim(str_replace(',', '', $new_budget->trimester_4));
				if(!$new_budget->trimester_4)
					$new_budget->trimester_4 = 0;

				$criteria=new CDbCriteria;
				$criteria->condition='csv_id = "'.$new_budget->csv_parent_id.'" AND year ='.$yearly_budget->year;
				$parent=Budget::model()->find($criteria);
				if($parent)
					$new_budget->parent = $parent->id;
				else
					$new_budget->parent = $yearly_budget->id;
				$new_budget->featured=0;

				$criteria=new CDbCriteria;
				$criteria->condition='csv_id = "'.$new_budget->csv_id.'" AND year ='.$yearly_budget->year;
				$budget=Budget::model()->find($criteria);
				if(!$budget){

					//$new_budget->validate();
					//echo CHtml::errorSummary($new_budget);
					//Yii::app()->end();

					$new_budget->save();
					$new_budgets = $new_budgets+1;
					continue;
				}
				$new_budget->featured=$budget->featured;
				$differences = $budget->compare($new_budget);
				if(count($differences) == 1)	// only difference is the id
					continue;

				foreach($differences as $attribute=>$values){
					if($attribute == 'id')
						continue;
					$budget->owner->$attribute=$values['new'];
				}
				$budget->save();
				$updated_budgets = $updated_budgets+1;
			}
		}else
			$error = 'File path not defined.';
		if($error)
			echo CJavaScript::jsonEncode(array('error'=>$error));
		else
			echo CJavaScript::jsonEncode(array('new_budgets'=>$new_budgets, 'updated_budgets'=>$updated_budgets));
	}


	public function actionDownload($id)
	{
		$model = new ImportCSV;
		if(list($file, $budgets) = $model->createCSV($id)){
			$download='<a href="'.$file->getWebPath().'">'.$file->getWebPath().'</a>';
			Yii::app()->user->setFlash('csv_generated', count($budgets).' budgets exported.<br />'.$download);

			$criteria=new CDbCriteria;
			$criteria->condition='parent IS NULL AND year='.$id;
			$this->redirect(array('/budget/updateYear', 'id'=>Budget::model()->find($criteria)->id));
		}
	}

	/**
	 * import budget descriptions
	 * upload csv to app/files/csv/descriptions.csv and call url csv/importDescriptions
	 */
	public function actionImportDescriptions()
	{
		$model = new ImportCSV;
		$mega_array = explode('|', file_get_contents($model->path.'descriptions.csv'));
		array_shift($mega_array);
		$header=1;
		while($mega_array){
			$field_cnt=0;
			$row=array();
			while($field_cnt < 7){
				$row[] = array_shift($mega_array);
				//echo $field_cnt.': '.$row[$field_cnt].'<br />';
				$field_cnt++;
			}
			if(!$header){
					$budget=new BudgetDescription;	
					$budget->csv_id = trim(trim($row[0], '"'));
					$budget->language = trim(trim($row[2], '"'));
					$budget->code = trim(trim($row[3], '"'));
					$budget->label = trim(trim($row[4], '"'));
					$budget->concept = trim(trim(trim($row[5], '"')),'.');
					$description=str_replace('"', '', $row[6]);
					$description=trim($description);
					$budget->description = nl2br($description);
					$budget->text = $description;
					$budget->common = 1;
										
					//$budget->validate();
					
					if(!$budget->save()){
						echo CHtml::errorSummary($budget);
						Yii::app()->end();
					}
			}else
				$header=0;
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Budget the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Budget::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Budget $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='budget-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
