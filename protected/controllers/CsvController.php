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
				'actions'=>array('importCSV','uploadCSV','checkCSVFormat',
				'addMissingValues'/*,'addMissingTotals','addMissingDescriptions',*/'checkCSVTotals','importCSVData',
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


	public function actionCheckCSVFormat()
	{
		if(!isset($_GET['csv_file'])){
			echo CJavaScript::jsonEncode(array('error'=>'CSV file path not defined.'));	
			Yii::app()->end();
		}
		$error=array();

		$model = new ImportCSV;
		$model->csv = $model->path.$_GET['csv_file'];
		
		/* Test encoding */
		if(!$model->checkEncoding()){
			echo CJavaScript::jsonEncode(array('error'=>'This file has not been saved as UTF-8'));
			Yii::app()->end();
		}
		
		$correct_field_delimiter=0;
		$ids = array();
		$lines = file($model->csv);
		foreach ($lines as $line_num => $line) {
			if($line_num==0){
				$delimiterCnt = substr_count($line, '|');
				if ($delimiterCnt == 0){
					$error[]='Delimiter | not found in file.';
					break;
				}
				if ($delimiterCnt != 9){
					$error[]=($delimiterCnt+1).' columns found. Expecting 10';
					break;
				}
				continue;
			}
			list($id, $code, $initial_prov, $actual_prov, $t1, $t2, $t3, $t4, $label, $concept) = explode("|", $line);
			$id = trim($id);
			if(in_array($id, $ids)) {
				$error[]='<br />Register '. ($line_num) .': Internal code "'.$id.'" is not unique';
			}
			if(!is_numeric(trim($initial_prov))){
				$error[]='<br />Register '. ($line_num) .': Initial provision is not numeric';
			}
			if(!is_numeric(trim($actual_prov))){
				$error[]='<br />Register '. ($line_num) .': Actual provision is not numeric';
			}
			if(!is_numeric(trim($t1))){
				$error[]='<br />Register '. ($line_num) .': Trimester 1 is not numeric';
			}
			if(!is_numeric(trim($t2))){
				$error[]='<br />Register '. ($line_num) .': Trimester 2 is not numeric';
			}
			if(!is_numeric(trim($t3))){
				$error[]='<br />Register '. ($line_num) .': Trimester 3 is not numeric';
			}
			if(!is_numeric(trim($t4))){
				$error[]='<br />Register '. ($line_num) .': Trimester 4 is not numeric';
			}
			$ids[]=$id;

		}
		if(!$error){
			$model->orderCSV();	
		}
		if($error)
			echo CJavaScript::jsonEncode(array('error'=>$error));
		else
			echo count($lines) - 1;
	}

	public function actionaddMissingValues()
	{
		if(!isset($_GET['csv_file'])){
			echo CJavaScript::jsonEncode(array('error'=>'CSV file path not defined.'));
			Yii::app()->end();
		}
		$model = new ImportCSV;
		$model->csv = $model->path.$_GET['csv_file'];
		
		$msg=Null;
		$newRegisterCnt = $model->addMissignRegisters();
		
		if($newRegisterCnt > 0){
			$msg='<span class="warn">'.$newRegisterCnt.' new registers added.</span>';
			$model->addMissingConcepts();
		}	
		if($new_totals = $model->addMissingTotals()){
			if($newRegisterCnt)
				$new_totals = $new_totals - (6 * $newRegisterCnt); // 6 because the are 6 number columns in csv
			if($new_totals)
				$msg = $msg.'<span class="warn"> '.$new_totals.' missing totals added</span>';
		}
		$new_concepts = 0;
		if($new_concepts = $model->addMissingConcepts())
			$msg = $msg.'<span class="warn"> '.$new_concepts.' codes/concepts added.</span>';
			
		if(!$msg)
			$msg='No missing values';
			
		echo CJavaScript::jsonEncode(array(	'updated'=>$newRegisterCnt,
											'new_concepts'=>$new_concepts,
											'msg'=>$msg,
											'file'=>Yii::app()->request->baseUrl.'/files/csv/'.$_GET['csv_file']
											));		
	}
/*
	public function actionAddMissingDescriptions()
	{
		if(!isset($_GET['csv_file'])){
			echo CJavaScript::jsonEncode(array('error'=>'CSV file path not defined.'));	
			Yii::app()->end();
		}
		$model = new ImportCSV;
		$model->csv = $model->path.$_GET['csv_file'];
		$updated = $model->addMissingConcepts();
		echo CJavaScript::jsonEncode(array('updated'=>$updated,'file'=>Yii::app()->request->baseUrl.'/files/csv/'.$_GET['csv_file']));
	}
*/
	public function actionCheckCSVTotals()
	{
		if(!isset($_GET['csv_file'])){
			echo CJavaScript::jsonEncode(array('error'=>'CSV file path not defined.'));	
			Yii::app()->end();
		}
		$model = new ImportCSV;
		$model->csv = $model->path.$_GET['csv_file'];
		$registers = $model->csv2array();
		
		$lines = file($model->csv);
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
		if(!$id){
			echo CJavaScript::jsonEncode(array('error'=>'Year not selected'));
			Yii::app()->end();
		}
		if(!isset($_GET['csv_file'])){
			echo CJavaScript::jsonEncode(array('error'=>'CSV file path not defined.'));
			Yii::app()->end();			
		}
		$criteria=new CDbCriteria;
		$criteria->condition='parent IS NULL AND year='.$id;
		$yearly_budget=Budget::model()->find($criteria);
		if(!$yearly_budget){
			echo CJavaScript::jsonEncode(array('error'=>'Selected Year '.$id.' does not exist in database.'));
			Yii::app()->end();
		}
		$error=Null;
		$new_budgets = 0;
		$updated_budgets = 0;

		$model = new ImportCSV;
		$model->csv = $model->path.$_GET['csv_file'];
		$lines = file($model->csv);
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

			$new_budget->initial_provision = trim($initial_prov);
			if(!$new_budget->initial_provision)
				$new_budget->initial_provision = 0;

			$new_budget->actual_provision = trim($actual_prov);
			if(!$new_budget->actual_provision)
				$new_budget->actual_provision = 0;

			$new_budget->trimester_1 = trim($t1);
			if(!$new_budget->trimester_1)
				$new_budget->trimester_1 = 0;

			$new_budget->trimester_2 = trim($t2);
			if(!$new_budget->trimester_2)
				$new_budget->trimester_2 = 0;

			$new_budget->trimester_3 = trim($t3);
			if(!$new_budget->trimester_3)
				$new_budget->trimester_3 = 0;

			$new_budget->trimester_4 = trim($t4);
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
