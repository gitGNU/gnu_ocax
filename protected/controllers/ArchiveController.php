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

class ArchiveController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
				'actions'=>array('validateFile','create','delete'),
				'expression'=>"Yii::app()->user->isPrivileged()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Downloads a particular model.
	 * @param integer $id the ID of the model to be downloaded
	 */
	public function actionView($id)
	{
		$model=$this->loadModel($id);

		if (file_exists($model->baseDir.$model->path)) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime_type = finfo_file($finfo, $model->baseDir.$model->path);
			
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-type: $mime_type");
			header("Content-Disposition: attachment; filename=\"".$model->name.".".$model->extension."\"");
			//header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($model->baseDir.$model->path));
			ob_end_flush();
			@readfile($model->baseDir.$model->path);
			exit;
		}
	}
	
	public function actionValidateFile()
	{
		$model=new Archive;
		// doing validation like this because I think I can't do it with ajax in a modal window
		if(isset($_GET['file_name']))
		{
			//$file_name = File::model()->normalize($_GET['file_name']);
			$file_name = $_GET['file_name'];
			$path = $model->baseDir.'/files/archive/'.$file_name;

			if(!$file_name)
				echo 'File required.';
			//elseif (!preg_match('/^[a-zA-Z0-9_\-]+\.[a-zA-Z]{3,4}$/', $file_name))
    	    //    echo '"'.$file_name.'" Only characters a-z A-Z and 0-9 are allowed. ej: file.pdf';
			elseif(file_exists($path))
    	        echo '"'.$file_name.'" File already uploaded';
			else
				echo 1;
			Yii::app()->end();
		}
		echo 'File required.';

	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Archive;

		if(isset($_POST['Archive']))
		{
			$model->attributes = $_POST['Archive'];
			$model->file = CUploadedFile::getInstance($model,'file');
			
			$model->name = $model->file->name;
			$model->path = '/files/archive/'.$model->file->name;
			$model->created = date('Y-m-d');
			$model->author = Yii::app()->user->getUserID();
			
			$saved = 0;
			if($model->file->saveAs($model->baseDir.$model->path)){
				$model->extension = $model->getExtension($model->baseDir.$model->path);
				if($model->extension){	// remove extension from name
					$model->name = substr($model->name , 0, (-1 * strlen($model->extension))-1 );
				}
				$saved = $model->save();
			}

			if($saved)
				Yii::app()->user->setFlash('success', __('File uploaded correctly'));
			else
				Yii::app()->user->setFlash('error', __('File uploaded failed'));
			
			$this->redirect(array('archive/index'));
		}
		echo $this->renderPartial('create',array('model'=>$model),false,true);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	/*
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Archive']))
		{
			$model->attributes=$_POST['Archive'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	*/

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		echo $id;
		$model = $this->loadModel($id);
		
		if(strpos($model->path, '/files/DatabaseDownload') !== 0)	// we don't delete the zip file
			$model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));

	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->pageTitle = Config::model()->findByPk('siglas')->value.' '.__('Archive');
		$model=new Archive('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Archive'])){
			$model->attributes=$_GET['Archive'];
		}
		$this->render('index',array(
			'dataProvider'=>$model->search(),
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	/*
	public function actionAdmin()
	{
		$model=new Archive('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Archive']))
			$model->attributes=$_GET['Archive'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	*/

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Archive the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Archive::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Archive $model the model to be validated
	 */
	/*
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='archive-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	*/
}

