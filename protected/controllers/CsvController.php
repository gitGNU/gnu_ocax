<?php

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
				'actions'=>array('importCSV','uploadCSV','checkCSVFormat','importCSVData'),
				'expression'=>"Yii::app()->user->isAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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

			$model->csv->saveAs($model->path.'text.csv');
			$model->csv = 'text.csv';
			$model->step = 2;
		}
		$this->render('importCSV', array('model'=>$model));
	}

	public function actionCheckCSVFormat()
	{
		$error=null;
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
						$error='Delimiter | not found in file.';
						break;
					}
				}
				list($id, $code, $label, $concept, $provision, $spent, $parent_id) = explode("|", $line);
				if (in_array($id, $ids)) {
					$error = 'Register '. ($line_num + 1) .': Internal code "'.$id.'" is not unique';
					break;
				}
				$ids[]=$id;
			}
		}else
			$error = 'File path not defined.';
		if($error)
			echo CJavaScript::jsonEncode(array('error'=>$error));
		else
			//echo CJavaScript::jsonEncode(array('ids'=>$ids));
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
		$error=null;
		$is_new=0;
		$is_updated=0;
		$new_budgets = 0;
		$updated_budgets = 0;
		if(isset($_GET['csv_file'])){
			$model = new ImportCSV;
			$lines = file($model->path.$_GET['csv_file']);
			foreach ($lines as $line_num => $line) {
				if($line_num==0)
					continue;
				list($csv_id, $code, $label, $concept, $provision, $spent, $csv_parent_id) = explode("|", $line);
				$csv_id = trim($csv_id);

				$criteria=new CDbCriteria;
				$criteria->condition='parent IS NOT NULL AND csv_id = "'.$csv_id.'" AND year ='.$yearly_budget->year;
				$budget=Budget::model()->find($criteria);
				if(!$budget){
					$is_new=1;
					$budget=new Budget;
					$budget->csv_id = $csv_id;
					$budget->year = $yearly_budget->year;
				}
				$budget->code = trim($code);
				$budget->label = trim($label);
				$budget->concept = trim($concept);
				$budget->provision = trim(str_replace('€', '', $provision));
				$budget->provision = trim(str_replace(',', '', $budget->provision));
				//$budget->provision = trim($provision);
				
				//$budget->spent = trim($spent);
				$budget->spent = trim(str_replace('€', '', $spent));
				if(!$budget->spent)
					$budget->spent = 0;

				$csv_parent_id = trim($csv_parent_id);

				if($csv_parent_id){
					$criteria=new CDbCriteria;
					$criteria->condition='csv_id="'.$csv_parent_id.'" AND year ='.$yearly_budget->year;
					$parent=Budget::model()->find($criteria);
					if($parent)
						$budget->parent = $parent->id;
				}else
					$budget->parent=$yearly_budget->id;
/*
				if(!$budget->validate()){
					foreach($budget->getErrors() as $msg)
						print_r($msg);
						echo '<br />';
				}
*/
				if($budget->id)
					$updated_budgets = $updated_budgets+1;
				else
					$new_budgets = $new_budgets+1;
				$budget->save();
			}
		}else
			$error = 'File path not defined.';
		if($error)
			echo CJavaScript::jsonEncode(array('error'=>$error));
		else
			echo CJavaScript::jsonEncode(array('new_budgets'=>$new_budgets, 'updated_budgets'=>$updated_budgets));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		//$dataProvider=new CActiveDataProvider('Partida',array('criteria'=>array('order'=>'weight ASC')));
		$this->render('import'/*,array('dataProvider'=>$dataProvider,)*/);
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
