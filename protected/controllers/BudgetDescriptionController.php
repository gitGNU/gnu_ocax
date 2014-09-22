<?php
/**
 * OCAX -- Citizen driven Observatory software
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

class BudgetDescriptionController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('getDescription'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('view','create','update','translate','modify','admin','delete'),
				'expression'=>"Yii::app()->user->canEditBudgetDescriptions()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionModify()
	{
		if(!isset($_GET['budget']))
			$this->redirect(Yii::app()->createUrl('user/panel'));

		$budget = Budget::model()->findByPk($_GET['budget']);
		if($model = BudgetDescLocal::model()->findByAttributes(array('csv_id'=>$budget->csv_id,'language'=>Yii::app()->language)))
			$this->redirect('update/'.$model->id);
		else
			$this->redirect('create?budget='.$budget->id);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new BudgetDescLocal;
		$model->setScenario('create');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(!isset($_GET['budget']))
			$this->redirect(Yii::app()->createUrl('site/index'));
			
		$budget = Budget::model()->findByPk($_GET['budget']);
		if($local = $model->findByAttributes(array('csv_id'=>$budget->csv_id,'language'=>Yii::app()->language))){
			$this->redirect(Yii::app()->createUrl('BudgetDescription/view/'.$local->id));
			Yii::app()->end();
		}

		$common_desc = BudgetDescCommon::model()->findByAttributes(array('csv_id'=>$budget->csv_id,'language'=>Yii::app()->language));
		if(!$common_desc)
			$common_desc = BudgetDescCommon::model()->findByAttributes(array('csv_id'=>$budget->csv_id));

		if($common_desc){
			$model->csv_id = $common_desc->csv_id;
			$model->language = $common_desc->language;
			$model->concept = $common_desc->concept;
			$model->code = $common_desc->code;
			$model->label = $common_desc->label;
			$model->description = $common_desc->description;
		}else{
			$model->csv_id = $budget->csv_id;
			$model->concept = $budget->concept;
			$model->code = $budget->code;
			$model->label = $budget->label;
			if(isset($_GET['lang']))
				$model->language = $_GET['lang'];
			else $model->language = getDefaultLanguage();
		}

		$this->pageTitle=CHtml::encode('Desc. '.$budget->csv_id);

		if(isset($_POST['BudgetDescLocal']))
		{
			$model->attributes=$_POST['BudgetDescLocal'];
			$model->text = str_replace("<br />", " ", $model->description);
			$model->text = trim(strip_tags($model->text));
			$model->csv_id = strtoupper($model->csv_id);
			$model->language = strtolower($model->language);
			$model->modified = date('c');
			if($model->save()){
				$this->redirect(Yii::app()->createUrl('BudgetDescription/view/'.$model->id));
			}
		}
		$this->render('create',array('model'=>$model,'budget_id'=>$budget->id));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BudgetDescLocal']))
		{
			$model->attributes=$_POST['BudgetDescLocal'];
			$model->text = str_replace("<br />", " ", $model->description);
			$model->text = trim(strip_tags($model->text));
			$model->modified = date('c');
			if($model->save()){
				$this->redirect(Yii::app()->createUrl('BudgetDescription/view/'.$model->id));
			}
		}
		$this->pageTitle=CHtml::encode('Desc. '.$model->csv_id);
		$this->render('update',array('model'=>$model));

	}

	public function actionTranslate()
	{
		if(!isset($_GET['lang']) && !isset($_GET['csv_id']))
			$this->redirect(Yii::app()->createUrl('BudgetDescription/admin'));

		elseif($desc = BudgetDescLocal::model()->findbyAttributes(array('language'=>$_GET['lang'],'csv_id'=>$_GET['csv_id'])))
			$this->redirect(Yii::app()->createUrl('BudgetDescription/view/'.$desc->id));

		else{
			$budget = Budget::model()->findByAttributes(array('csv_id'=>$_GET['csv_id']));
			$this->redirect(Yii::app()->createUrl('BudgetDescription/create?budget='.$budget->id.'&lang='.$_GET['lang']));
		}
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
		$dataProvider=new CActiveDataProvider('BudgetDescLocal');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new BudgetDescLocal('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BudgetDescLocal']))
			$model->attributes=$_GET['BudgetDescLocal'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BudgetDescLocal the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BudgetDescLocal::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BudgetDescription $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='budget-description-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
