<?php

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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('getBudgetDetails'/*'create','update'*/),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(	'admin','create','adminYears',
									'createYear','updateYear','update','delete',
									),
				'expression'=>"Yii::app()->user->isAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function actionGetBudgetDetails($id)
	{
		//if(!Yii::app()->request->isAjaxRequest)
		//	Yii::app()->end();

		$model=$this->loadModel($id);
		if($model){
			echo CJavaScript::jsonEncode(array(	'html'=>$this->renderPartial('_consultaView',array('model'=>$model),true,true),
												'code'=>$model->code));
		}else
			echo 0;
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */

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
		$parent_id=Null;
		if(!$model->parent && isset($_GET['parent_id'])){
			$parent_id=$_GET['parent_id'];
		}
		echo CJavaScript::jsonEncode(array('html'=>$this->renderPartial('create',array('model'=>$model,'parent_id'=>$parent_id),true,true)));
	}

	public function actionCreateYear()
	{
		$model=new Budget;
		$model->scenario = 'newYear';

		$model->concept = 'Partida raiz';
		$model->code = 0;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Budget']))
		{
			$model->attributes=$_POST['Budget'];

			if($model->save()){
				$this->redirect(array('adminYears'));
			}
		}
		if(!$model->year){
			if(Yii::app()->user->hasFlash('badYear')){
				$model->year=Yii::app()->user->getFlash('badYear');
				Yii::app()->user->setFlash('badYear', $model->year);
			}else
				$model->year = Config::model()->findByPk('year')->value;
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

	public function actionUpdateYear($id)
	{
		$model=$this->loadModel($id);

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

		$this->render('updateYear',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
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

	public function actionAdminYears()
	{
		$years=new CActiveDataProvider('Budget',array(
			'criteria'=>array('condition'=>'parent IS NULL',
			'order'=>'year DESC'),
		));
		$this->render('adminYears',array('years'=>$years,));
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
		$this->layout='//layouts/column1';
		//$dataProvider=new CActiveDataProvider('Partida',array('criteria'=>array('order'=>'weight ASC')));
		$this->render('index'/*,array('dataProvider'=>$dataProvider,)*/);
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
