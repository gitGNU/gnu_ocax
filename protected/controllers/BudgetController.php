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

class BudgetController extends Controller
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
			'postOnly + delete,restoreBudgets', // we only allow deletion via POST request
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','getPieData','getChildBars','getBudgetDetailsForBar',
									'getBudget','getAnualComparison'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('getBudgetDetails'),
				'expression'=>"Yii::app()->user->isTeamMember()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(	'getTotalYearlyBudgets','adminYears','deleteYearsBudgets',
									'createYear','updateYear','featured','feature',/*'update',*/'delete',
									'dumpBudgets','restoreBudgets',
									'noDescriptions'),
				'expression'=>"Yii::app()->user->isAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function actionGetTotalYearlyBudgets($id)
	{
		$model=$this->loadModel($id);
		$budgets= Budget::model()->findAllBySql('SELECT id FROM budget WHERE year = '.$model->year.' AND parent IS NOT NULL');
		echo count($budgets);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		
		$model=$this->loadModel($id);
		$this->pageTitle=CHtml::encode(__('Budget').': '.$model->title);
		$this->render('view',array(
			'model'=>$model,
		));
	}

	public function actionGetBudget($id)
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		$model=$this->loadModel($id);
		if($model)
			echo $this->renderPartial('view',array('model'=>$model),true,true);
		else
			echo 0;
	}

	public function actionGetBudgetDetailsForBar($id)
	{
		//if(!Yii::app()->request->isAjaxRequest)
		//	Yii::app()->end();

		$model=$this->loadModel($id);
		if($model){
			echo '<div class="budget_details" budget_id='.$model->id.' style="width:543px;padding:0px">';
			echo $this->renderPartial('_enquiryView',array(	'model'=>$model,
															'showCreateEnquiry'=>1,
															'showLinks'=>1),false,true);
			echo '</div>';
		}else
			echo 0;
	}

	public function actionGetChildBars($id)
	{
		$model=$this->loadModel($id);
		$this->renderPartial('childBars', array('model'=>$model,'indent'=>$_GET['indent'],'globals'=>$_GET['globals']),false,true);
	}

	public function actionGetPieData($id)
	{
		$model=$this->loadModel($id);
		$graphThisModel=$model;
		$goBackID=$model->parent0->id;
		$isParent=1;
		$hideConcept=1;
		if(!$model->budgets){
			$isParent=0;
			$graphThisModel=$model->parent0;
			$goBackID=$model->parent0->parent0->id;
			$hideConcept=Null;
		}
		if($model->parent0->featured && !$isParent)
			$goBackID = Null;
		
		$params=array(	'parent_id'=>$model->parent,
						'title'=>$graphThisModel->getConcept(),
						'budget_details'=>	'<div class="budget_details" style="padding:0px">'.
											$this->renderPartial('_budgetDetails',array('model'=>$model,
																						'showCreateEnquiry'=>1,
																						'showLinks'=>1,
																						'hideConcept'=>$hideConcept),
																						true,false).
											'</div>',
						'is_parent'=>$isParent,
						'go_back_id'=>$goBackID,
						'actual_provision'=>(int)$graphThisModel->actual_provision,
					);
		$data=array();
		$childBudgets=$graphThisModel->getChildBudgets();
		foreach($childBudgets as $budget){
			$data[] = array(
							'<span class="link legend_item" budget_id="'.$budget->id.'">'.$budget->code.'. '.$budget->getConcept().'</span>',
							(int)$budget->actual_provision,
							$budget->id,
							format_number($budget->actual_provision),
						);
		}
		$result=array('data'=>$data, 'params'=>$params,);
		
		if(Yii::app()->request->isAjaxRequest)
			echo CJavaScript::jsonEncode($result);
		else
			return CJavaScript::jsonEncode($result);
	}

	/**
	 * team_member uses this to change enquiry type.
	 */	
	public function actionGetBudgetDetails($id)
	{
		//if(!Yii::app()->request->isAjaxRequest)
		//  Yii::app()->end();
 
		$model=$this->loadModel($id);
		if($model){
			echo CJavaScript::jsonEncode($this->renderPartial('_enquiryView',array('model'=>$model),true,true));
		}else
			echo 0;
	} 

	public function actionGetAnualComparison($id)
	{
		$model=$this->loadModel($id);
		if($model){
			echo CJavaScript::jsonEncode($this->renderPartial(	'_compareYears',
																array(	'model'=>$model,
																		'budgets'=>$model->getAllBudgetsWithCSV_ID()),
																		true,true)
															);
		}else
			echo 0;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	 /*
	public function actionCreate()
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		$model=new Budget;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Budget']))
		{
			$model->attributes=$_POST['Budget'];
			if($model->save())
				echo 1;
			else
				echo 0;
			Yii::app()->end();
		}
		if(!$model->parent && isset($_GET['parent_id']) && !$model->year){
			$parent=$model->findByPk($_GET['parent_id']);
			if($parent){
				$model->parent=$parent->id;
				$model->csv_parent_id=$parent->csv_id;
				$model->year=$parent->year;
			}
		}
		echo CJavaScript::jsonEncode(array('html'=>$this->renderPartial('create',array('model'=>$model),true,true)));
	}
	*/

	public function actionCreateYear()
	{
		$model=new Budget;
		$model->scenario = 'newYear';
		$model->initial_provision = '';	// we use this to story the city's population
		$model->actual_provision = 0;
		$model->trimester_1 = 0;
		$model->trimester_2 = 0;
		$model->trimester_3 = 0;
		$model->trimester_4 = 0;
		$model->concept = 'Root budget';
		$model->code = 0;	// 0 = not published, 1 = published

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Budget']))
		{
			$model->attributes=$_POST['Budget'];
			if($model->save()){
				$this->redirect(array('adminYears'));
			}
		}
		$this->render('createYear',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */

	/*
	public function actionUpdate($id)
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Budget']))
		{
			$model->attributes=$_POST['Budget'];
			if($model->save())
				echo 1;
			else
				echo 0;
			Yii::app()->end();
		}
		echo CJavaScript::jsonEncode(array('html'=>$this->renderPartial('update',array('model'=>$model),true,true)));
	}
	*/
	
	/**
	 * List budgets without corresponding budgetDescription.
	 */
	public function actionNoDescriptions()
	{
		$model=new Budget('budgetsWithoutDescription');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Budget']))
			$model->attributes=$_GET['Budget'];

		$this->render('noDescriptions',array(
			'model'=>$model,
		));
	}

	public function actionUpdateYear($id)
	{
		$model=$this->loadModel($id);
		Yii::app()->request->cookies['year'] = new CHttpCookie('year', $model->year);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Budget']))
		{
			$model->attributes=$_POST['Budget'];
			if($model->save()){
				$years=new CActiveDataProvider('Budget',array(
					'criteria'=>array('condition'=>'parent IS NULL',
					'order'=>'year DESC'),
				));
				$this->redirect(array('adminYears'));
			}
		}

		$criteria = array(
			'with'=>array('budget0'),
			'condition'=>' budget0.year = '.$model->year,
			'together'=>true,
		);
		$enquirys = new CActiveDataProvider(Enquiry::model(), array('criteria'=>$criteria,));

		$this->render('updateYear',array(
			'model'=>$model,'enquirys'=>$enquirys,));
	}

	/**
	 * Manages all models.
	 */
	 /*
	public function actionAdmin()
	{
		$model=new Budget('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['year']))
			$model->year=$_GET['year'];
		else
			$model->year=Config::model()->findByPk('year')->value;
		if(isset($_GET['Budget']))
			$model->attributes=$_GET['Budget'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	*/

	public function actionFeatured($id)
	{
		$model=new Budget('featuredSearch');
		$model->unsetAttributes();  // clear any default values
		if(isset($id))
			$model->year=$id;
		else
			$model->year=Config::model()->findByPk('year')->value;
		if(isset($_GET['Budget']))
			$model->attributes=$_GET['Budget'];

		$this->render('featured',array(
			'model'=>$model,
		));
	}

	public function actionFeature($id)
	{
		$model = $this->loadModel($id);
		// we don't show graphs of budgets that don't have children
		if(!$model->budgets){
			echo 1;
			return;
		}	
		if($model->featured)
			$model->featured=0;
		else
			$model->featured=1;
		$model->save();
		echo 1;
	}


	public function actionAdminYears()
	{
		$years=new CActiveDataProvider('Budget',array(
			'criteria'=>array('condition'=>'parent IS NULL',
			'order'=>'year DESC'),
		));
		$this->render('adminYears',array('years'=>$years,));
	}


	public function actionDeleteYearsBudgets($id)
	{
		$model = $this->loadModel($id);

		$criteria=new CDbCriteria;
		$criteria->condition = 'year = '.$model->year.' AND parent IS NOT NULL';
		$criteria->order = 'csv_id DESC';

		$budgets = $model->findAll($criteria);
		$total=count($budgets);

		while($budgets){
			foreach($budgets as $budget){
				if(Enquiry::model()->findByAttributes(array('budget'=>$budget->id)))
					continue;
				if(!$model->findByAttributes(array('parent'=>$budget->id)))
					$budget->delete();
			}
			$budgets = $model->findAll($criteria);
			$new_total=count($budgets);
			if($total == $new_total)
				break;
			else
				$total = $new_total;
		}
		$this->redirect(array('updateYear','id'=>$model->id));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		$root_budget=0;
		if(!$model->parent)
			$root_budget=1;

		if(!($model->findByPk($model->parent) || Enquiry::model()->findByAttributes(array('budget'=>$model->id)))){
			$model->delete();
			if($root_budget){
				Yii::app()->user->setFlash('success',__('Year deleted'));
				$this->redirect(array('adminYears'));
			}
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->layout='//layouts/column1';
		$this->pageTitle=__('Budgets').' '.Config::model()->findByPk('councilName')->value;
		$model = new Budget('publicSearch');

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['year'])){
			$model->year = $_GET['year'];
			Yii::app()->request->cookies['year'] = new CHttpCookie('year', $model->year);
		}
		elseif(isset(Yii::app()->request->cookies['year']))
			$model->year = Yii::app()->request->cookies['year']->value;
		else
			$model->year = Config::model()->findByPk('year')->value;

		if(isset($_GET['graph_type'])){
			$graph_type=$_GET['graph_type'];
			Yii::app()->request->cookies['graph_type'] = new CHttpCookie('graph_type', $graph_type);
		}
		elseif(isset(Yii::app()->request->cookies['graph_type']))
			$graph_type=Yii::app()->request->cookies['graph_type']->value;
		else
			$graph_type='pie';

		if (isset($_GET['Budget'])) {
			$model->attributes = $_GET['Budget'];
		}

		$this->render('index', array(
			'model' => $model,
			'graph_type' => $graph_type,
		));
	}

	/**
	 * Dump the budget table
	 */
	public function actionDumpBudgets()
	{
		echo Budget::model()->dumpBudgets();
	}

	/**
	 * Restore the budget table
	 */
	public function actionRestoreBudgets($id)
	{
		$result = Budget::model()->restoreBudgets($id);
		if($result == 0){
			Yii::app()->user->setFlash('success',__('Database restored correctly'));
		}
		return $result;
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
