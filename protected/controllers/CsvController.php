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
				'actions'=>array('importCSV','uploadCSV','checkCSVFormat','checkCSVTotals','importCSVData','download'),
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

			$model->csv->saveAs($model->path.$model->year.'-internal.csv');
			$model->csv = $model->year.'-internal.csv';
			$model->step = 2;
		}
		$this->render('importCSV', array('model'=>$model));
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
				list($id, $code, $label, $concept, $initial_prov, $actual_prov, $s_t1, $s_t2, $s_t3, $s_t4, $parent_id) = explode("|", $line);
				$id = trim($id);
				$parent_id=trim($parent_id);
				if (in_array($id, $ids)) {
					$error[]='<br />Register '. ($line_num) .': Internal code "'.$id.'" is not unique';
				}
				if ($parent_id != "" && !in_array($parent_id, $ids)) {
					$error[]='<br />Register '. ($line_num) .': Internal parent code "'.$parent_id.'" does not exist';
				}
				$ids[]=$id;
			}
		}else
			$error[]='File path not defined.';
		if($error)
			echo CJavaScript::jsonEncode(array('error'=>$error));
		else
			//echo CJavaScript::jsonEncode(array('ids'=>$ids));
			echo count($lines) - 1;
	}

	public function actionCheckCSVTotals()
	{
		$error=array();
		if(isset($_GET['csv_file'])){
			$model = new ImportCSV;
			$lines = file($model->path.$_GET['csv_file']);
			$ids = array();
			foreach ($lines as $line_num => $line) {
				if($line_num==0)
					continue;
				list($id, $code, $label, $concept, $initial_prov, $actual_prov, $s_t1, $s_t2, $s_t3, $s_t4, $parent_id) = explode("|", $line);
				$id = trim($id);
				$parent_id=trim($parent_id);
				$ids[$id]=array();
				$initial_prov = str_replace('€', '', $initial_prov);
				$initial_prov = (float)trim(str_replace(',', '', $initial_prov));
				$ids[$id]['internal_code']=$id;
				$ids[$id]['total']=$initial_prov;
				$ids[$id]['children']=array();
				if(array_key_exists($parent_id, $ids)){
					$ids[$parent_id]['children'][$id]=$initial_prov;
				}
			}
		}else
			$error[]='File path not defined.';
		//check totals
		foreach($ids as $id){
			if($id['children']){
				$child_total = 0;
				foreach($id['children'] as $child => $total)
					$child_total = $child_total + $total;
				if(bccomp($child_total, $id['total'])!=0){
					$errorStr='<div style="margin-top:15px;width:400px;">';
					$errorStr=$errorStr.'<b>'.$id['internal_code'].' Initial provision is: <span style="float:right;">'.number_format($id['total'], 2).'</span></b>';
					$rowColor='';
					foreach($id['children'] as $child => $total){
						if(!$rowColor){
							$rowColor='style="background-color:#EBEBEB;"';
						}else
							$rowColor='';
						$errorStr=$errorStr.'<div '.$rowColor.'>'.$child.'<span style="float:right;">'.number_format($total, 2).'</span></div>';
					}
					$errorStr=$errorStr.'<span style="float:right;text-decoration: underline overline;">Total: '.number_format($child_total, 2).'</span>';
					$errorStr=$errorStr.'</div>';
					$error[]=$errorStr;
				}
			}
		}


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
		$error=Null;
		$new_budgets = 0;
		$updated_budgets = 0;
		if(isset($_GET['csv_file'])){
			$model = new ImportCSV;
			$lines = file($model->path.$_GET['csv_file']);
			foreach ($lines as $line_num => $line) {
				if($line_num==0)
					continue;
				list($csv_id, $code, $label, $concept, $initial_prov, $actual_prov, $s_t1, $s_t2, $s_t3, $s_t4, $csv_parent_id) = explode("|", $line);

				$new_budget=new Budget;
				$new_budget->csv_id = $csv_id;
				$new_budget->csv_parent_id = trim($csv_parent_id);
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

				$new_budget->spent_t1 = trim(str_replace('€', '', $s_t1));
				$new_budget->spent_t1 = trim(str_replace(',', '', $new_budget->spent_t1));
				if(!$new_budget->spent_t1)
					$new_budget->spent_t1 = 0;

				$new_budget->spent_t2 = trim(str_replace('€', '', $s_t2));
				$new_budget->spent_t2 = trim(str_replace(',', '', $new_budget->spent_t2));
				if(!$new_budget->spent_t2)
					$new_budget->spent_t2 = 0;

				$new_budget->spent_t3 = trim(str_replace('€', '', $s_t3));
				$new_budget->spent_t3 = trim(str_replace(',', '', $new_budget->spent_t3));
				if(!$new_budget->spent_t3)
					$new_budget->spent_t3 = 0;

				$new_budget->spent_t4 = trim(str_replace('€', '', $s_t4));
				$new_budget->spent_t4 = trim(str_replace(',', '', $new_budget->spent_t4));
				if(!$new_budget->spent_t4)
					$new_budget->spent_t4 = 0;

				$criteria=new CDbCriteria;
				$criteria->condition='csv_id = "'.$new_budget->csv_parent_id.'" AND year ='.$yearly_budget->year;
				$parent=Budget::model()->find($criteria);
				if($parent)
					$new_budget->parent = $parent->id;
				else
					$new_budget->parent = $yearly_budget->id;

				$criteria=new CDbCriteria;
				$criteria->condition='csv_id = "'.$new_budget->csv_id.'" AND year ='.$yearly_budget->year;
				$budget=Budget::model()->find($criteria);
				if(!$budget){
					$new_budget->save();
					$new_budgets = $new_budgets+1;
					continue;
				}
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
		$model->year = $id;

		$header = 'internal code|code|label|concept|initial provision|actual provision|spent t1|spent t2|spent t3|spent t4|internal parent code'.PHP_EOL;

		$file = '/tmp/csv-' . mt_rand(10000,99999);
		$fh = fopen($file, 'w');
		fwrite($fh, $header);

		$budgets = Budget::model()->findAllBySql('	SELECT csv_id, code, label, concept, initial_provision, actual_provision,
													spent_t1, spent_t2, spent_t3, spent_t4, csv_parent_id
													FROM budget
													WHERE year = '.$model->year.' AND parent IS NOT NULL');
		foreach($budgets as $b){
			fwrite($fh, $b->csv_id.'|'.$b->code.'|'.$b->label.'|'.$b->concept.'|'.$b->initial_provision.'|'.$b->actual_provision.
						'|'.$b->spent_t1.'|'.$b->spent_t2.'|'.$b->spent_t3.'|'.$b->spent_t4.'|'.$b->csv_parent_id. PHP_EOL);
		}
		fclose($fh);
		if (copy($file, $model->path.$model->year.'-internal.csv')) {
			unlink($file);
		}

		$link=Yii::app()->request->baseUrl.'/files/csv/'.$model->year.'-internal.csv';
		$download='<a href="'.$link.'">'.$link.'</a>';
		Yii::app()->user->setFlash('csv_generated', count($budgets).' budgets exported.<br />'.$download);

		$criteria=new CDbCriteria;
		$criteria->condition='parent IS NULL AND year='.$model->year;
		$this->redirect(array('/budget/updateYear', 'id'=>Budget::model()->find($criteria)->id));
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
