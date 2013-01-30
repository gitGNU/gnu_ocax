<?php

class CmspageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	public $defaultAction = 'admin';

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
				'actions'=>array('show'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('admin','delete','create','update','view'),
				'expression'=>"Yii::app()->user->isEditor()",
			),
/*
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
*/
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
		$this->layout='//layouts/column1';
		$model = $this->loadModel($id);
		if($model){
			$items = CmsPage::model()->findAllByAttributes(array('block'=>$model->block), array('order'=>'weight'));
			$this->render('show',array(
				'model'=>$model,
				'items'=>$items,
			));
		}
	}

	public function actionShow($pagename)
	{
		$this->layout='//layouts/column1';
		$model = CmsPage::model()->findByAttributes(array('pagename'=>$pagename));
		if($model){
			$items = CmsPage::model()->findAllByAttributes(array('block'=>$model->block), array('order'=>'weight'));
			$this->render('show',array(
				'model'=>$model,
				'items'=>$items,
			));
		}else
			throw new CHttpException(404,'The requested page does not exist.');
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new CmsPage;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CmsPage']))
		{
			$model->attributes=$_POST['CmsPage'];
			$model->pagename = str_replace(' ', '-', $model->pagename);
			$model->pagename = strtolower($model->pagename);
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
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
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CmsPage']))
		{
			$model->attributes=$_POST['CmsPage'];
			$model->pagename = str_replace(' ', '-', $model->pagename);
			$model->pagename = strtolower($model->pagename);
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
/*
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('CmsPage');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
*/
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CmsPage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CmsPage']))
			$model->attributes=$_GET['CmsPage'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=CmsPage::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cms-page-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
