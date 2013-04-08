<?php

class FileController extends Controller
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
				'actions'=>array(/*'index','view'*/),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(/*'create','update'*/),
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('showCMSfiles'),
				'expression'=>"Yii::app()->user->isEditor()",
			),
			array('allow',
				'actions'=>array(/*'view',*/'create','validateFileName',/*'update','admin',*/'delete'),
				'expression'=>"Yii::app()->user->isEditor() || Yii::app()->user->isTeamMember()",
			),
			array('allow',
				'actions'=>array('showBudgetFiles'),
				'expression'=>"Yii::app()->user->isAdmin()",
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
/*
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
*/

	private function getPath($modelName,$modelID=Null){
		$path=$modelName;
		if($modelName == 'Reply')
			$path=$path.'/'.$modelID;
		return $path;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new File;

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['File']))
		{
			$model->attributes=$_POST['File'];
			$model->file=CUploadedFile::getInstance($model,'file');
			if($model->file){
				$path=$this->getPath($model->model,$model->model_id);
				$model->uri=$model->baseDir.$path;

				if(!is_dir($model->uri))
					mkdir($model->uri, 0700, true);

				$normalized_name = $model->normalize($model->file->name);
				$model->uri=$model->uri.'/'.$normalized_name;
				$model->webPath=Yii::app()->request->baseUrl.'/files/'.$path.'/'.$normalized_name;

				if(!$model->name)
					$model->name=$model->file->name;

				$model->save();
				$model->file->saveAs($model->uri);

				if($model->model == 'CmsPage'){
					Yii::app()->user->setFlash('success', 'File uploaded correctly');
					$this->redirect(array('cmspage/admin'));
				}elseif($model->model == 'Reply'){
					$enquiry = Enquiry::model()->findByPk(Reply::model()->findByPk($model->model_id)->enquiry);
					$enquiry->promptEmail();
					$this->redirect(array('enquiry/teamView','id'=>$enquiry->id));
				}else
					$this->redirect(array('site/index'));
			}
		}

		if(isset($_GET['model']))
			$model->model=$_GET['model'];

		if(isset($_GET['model_id']))
			$model->model_id=$_GET['model_id'];

		echo $this->renderPartial('create',array('model'=>$model),false,true);
	}

	public function actionValidateFileName()
	{
		$model=new File;
		// doing validation like this because I think I can't do it with ajax in a modal window
		if(isset($_GET['file_name']))
		{
			$file_name = $model->normalize($_GET['file_name']);
			$path=$model->baseDir.$this->getPath($_GET['model'],$_GET['model_id']).'/'.$file_name;

			if(!$file_name)
				echo 'File required.';
			elseif (!preg_match('/^[a-zA-Z0-9_\-]+\.[a-zA-Z]{3,4}$/', $file_name))
    	        echo '"'.$file_name.'" Only characters a-z A-Z and 0-9 are allowed. ej: file.pdf';
			elseif(file_exists($path))
    	        echo '"'.$file_name.'" File already uploaded';
			else
				echo 1;
			Yii::app()->end();
		}
		echo 'File required.';
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		$model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		//if(!isset($_GET['ajax']))
		//	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionShowCMSfiles()
	{
		echo $this->renderPartial('showCMSfiles',array(),false,true);
	}
	public function actionShowBudgetFiles()
	{
		echo $this->renderPartial('showBudgetFiles',array(),false,true);
	}

	/**
	 * Manages all models.
	 */
/*
	public function actionAdmin()
	{
		$model=new File('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['File']))
			$model->attributes=$_GET['File'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
*/

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return File the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=File::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param File $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='file-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
