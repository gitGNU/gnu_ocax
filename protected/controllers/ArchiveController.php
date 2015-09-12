<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2015 OCAX Contributors. See AUTHORS.

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
				'actions'=>array('validateFile','uploadFile','createContainer','update','getDestinations','move','delete'),
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
	
	public function actionValidateFile($id = Null)
	{
		$model=new Archive;
		$containerID = $id;
		if ($container = $model->findByPk($containerID)){
			$containerPath = $container->path.'/';
		}else{
			$containerPath = $model->archiveRoot;
		}
		
		// doing validation like this because I think I can't do it with ajax in a modal window
		if(isset($_GET['file_name']))
		{
			$file_name = $_GET['file_name'];
			$path = $model->baseDir.$containerPath.$file_name;

			if (!$file_name){
				echo 'File required.';
			}
			elseif (file_exists($path)){
    	        echo '"'.$file_name.'" File already uploaded';
			}else{
				echo 1;
			}
			Yii::app()->end();
		}
		echo 'File required.';

	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUploadFile($id = Null)
	{
		$model=new Archive;
		
		$model->container = $id;
		if ($container = $model->findByPk($model->container)){
			$containerPath = $container->path.'/';
		}else{
			$containerPath = $model->archiveRoot;
		}
		
		if(isset($_POST['Archive']))
		{
			$model->setScenario('uploadFile');
			$model->attributes = $_POST['Archive'];
			$model->file = CUploadedFile::getInstance($model,'file');
			
			$model->name = $model->file->name;
			$model->path = $containerPath.$model->file->name;
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

			if ($saved){
				Log::model()->write('Archive',__('Uploaded').' "'.$model->name.'.'.$model->extension.'"');
				Yii::app()->user->setFlash('success', __('File uploaded correctly'));
			}else{
				Yii::app()->user->setFlash('error', __('File uploaded failed'));
			}
			$this->redirect(array($model->getParentContainerWebPath()));
		}
		echo $this->renderPartial('_uploadFile',array('model'=>$model),false,true);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateContainer($id = Null)
	{
		$model=new Archive;
		$model->container = $id;

		if (isset($_POST['Archive']))
		{
			$model->setScenario('createContainer');
			$model->attributes = $_POST['Archive'];			

			$model->buildPathFromName();
			
			if (file_exists($model->baseDir.$model->path)) {
				Yii::app()->user->setFlash('error', __('Folder already exists'));
				$this->redirect(array('archive/index/'.$model->getWebPath()));
			}
			if (!createDirectory($model->baseDir.$model->path)){
				Yii::app()->user->setFlash('error', __('Cannot create folder'));
				$this->redirect(array($model->getParentContainerWebPath()));
			}
					
			$model->is_container = 1;
			$model->created = date('Y-m-d');
			$model->author = Yii::app()->user->getUserID();
			
			if ($model->save()){
				Log::model()->write('Archive',__('Folder created').' "'.$model->path.'"');
				Yii::app()->user->setFlash('success', __('Folder created correctly'));
			}else{
				rmdir($model->baseDir.$model->path);
				Yii::app()->user->setFlash('error', __('New folder failed'));
			}
			$this->redirect(array($model->getParentContainerWebPath()));
		}
		echo $this->renderPartial('_createContainer',array('model'=>$model),false,true);
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		
		if (strpos($model->path, '/files/DatabaseDownload') === 0){ // we don't edit this archive
			echo 0;
			Yii::app()->end();
		}

		if(isset($_POST['Archive']))
		{
			$save = False;
			$oldName = $model->name;
			$model->attributes = $_POST['Archive'];
			
			if ($model->name != $oldName){
				$model->rename();
			}else{
				$model->save();
			}
			$this->redirect(array($model->getParentContainerWebPath()));
		}	
		echo $this->renderPartial('_editArchive',array('model'=>$model),false,true);
	}

	public function actionGetDestinations($id)
	{
		$model = $this->loadModel($id);

		$criteria=new CDbCriteria;
		$criteria->addCondition("is_container = 1");
		$criteria->order = 'path ASC';

		$containers=$model->findAll($criteria);
		$result = '<span class="link" onClick="js:moveArchive(\'0\')">/index</span>';

		foreach ($containers as $container){
			if ($container->id == $model->id || $container->isChildOf($model)){
				continue;
			}
			$path = '/d/'.str_replace($container->archiveRoot, '',$container->getWebPath()); 
			$result .= '<br /><span class="link" onClick="js:moveArchive(\''.$container->id.'\')">'.$path.'</span>';
		}
		echo $result;
	}

	public function actionMove($id, $destination_id)
	{
		if ($id == $destination_id){
			echo "id = $id , dest = $destination_id";
			Yii::app()->end();
		}
		$model = $this->loadModel($id);
		if($destination_id == 0){
			$destination_path = $model->archiveRoot;
			$destination_id = Null;
		}else{
			$destination = $this->loadModel($destination_id);
			$destination_path = $destination->path.'/';
		}
		
		$oldPath = $model->path;
		$newPath = $destination_path.strtolower(str_replace(' ', '-', trim(string2ascii($model->name))));
		
		if (!$model->is_container && $model->extension){
			$newPath .= '.'.$model->extension;
		}
		if ($oldPath == $newPath){	// already exists
			echo "id = $id , dest = $destination_id";
			Yii::app()->end();
		}
		if (! file_exists($model->baseDir.$oldPath) || file_exists($model->baseDir.$newPath) ){
			echo 0;
			Yii::app()->end();
		}
		if (rename($model->baseDir.$oldPath, $model->baseDir.$newPath)){
			$model->path = $newPath;
			$model->container = $destination_id;
			if ($model->save()){
				echo 1;
				Yii::app()->end();
			}
			rename($this->baseDir.$newPath, $this->baseDir.$oldPath);
		}
		echo 0;
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$is_container = $model->is_container;
		
		if ($is_container){
			$itemName = $model->path;
		}else{
			$itemName = $model->name;
			if($model->extension){
				$itemName .= '.'.$model->extension;
			}
		}
		if (strpos($model->path, '/files/DatabaseDownload') !== 0){	// we don't delete the zip file
			$model->delete();
			if($is_container){
				Log::model()->write('Archive',__('Deleted folder').' "'.$itemName.'"');
			}else{
				Log::model()->write('Archive',__('Deleted').' "'.$itemName.'"');
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])){
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}

	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($folder = Null)
	{
		$this->pageTitle = Config::model()->findByPk('siglas')->value.' '.__('Archive');
		$model=new Archive('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Archive'])){
			$model->attributes=$_GET['Archive'];
		}
		$container = $model->getContainerFromPath($folder);
		$this->render('index',array(
			'dataProvider'=>$model->search($container),
			'model'=>$model,
			'container' => $container,
		));
	}

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

