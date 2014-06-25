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

class VaultController extends Controller
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
				'actions'=>array('verifyKey', 'getSchedule'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('view', 'admin', 'index', 'create', 'update', 'delete'),
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
	public function actionView($id)
	{
		//if(!Yii::app()->request->isAjaxRequest)
		//	Yii::app()->end();

		$model=$this->loadModel($id);
		if($model){
			$backups = Backup::model()->getDataproviderByVault($model->id);
			if(Yii::app()->request->isAjaxRequest)
				echo $this->renderPartial('view',array('model'=>$model,'backups'=>$backups),true,true);
			else
				$this->render('view',array('model'=>$model,'backups'=>$backups));
		}
		else
			echo 0;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Vault;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->schedule='0000000';
		if(isset($_POST['Vault']))
		{
			$model->attributes=$_POST['Vault'];
			$model->host = rtrim($model->host, '/');
			$model->created = date('c');
			$model->state=0;
			if($model->save()){
				//$backups = Backup::model()->getDataproviderByVault($model->id);
				//$this->render('view',array('model'=>$model,'backups'=>$backups));
				//$backups = Backup::model()->getDataproviderByVault($model->id);
				$this->redirect(array('view','id'=>$model->id));
			}
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

		if(isset($_POST['Vault']))
		{
			$model->attributes=$_POST['Vault'];
			if($model->state == CREATED && $model->key){
				$model->setScenario('newKey');
				if($model->validate()){
					if($model->type == REMOTE && $model->state < VERIFIED){
						$opts = array('http' => array(
												'method'  => 'GET',
												'header'  => 'Content-type: application/x-www-form-urlencoded',
												'ignore_errors' => '1',
												'timeout' => 2.5,
												'user_agent' => 'ocax-'.getOCAXVersion(),
											));
						$vaultName = rtrim($model->host2VaultName(Yii::app()->getBaseUrl(true)), '-remote');
						$context = stream_context_create($opts);
						
						$reply=Null;
						$reply = @file_get_contents($model->host.'/vault/verifyKey?key='.$model->key.'&vault='.$vaultName, false, $context);
						if($reply == 1){
							$model->state = VERIFIED;
							$model->saveKey();
							$model->save();
							$this->redirect(array('view','id'=>$model->id));
						}
					}
				}
			}
			elseif($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		$this->render('view',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Part of the vault handshake
	 * Remote ocax instalation calls this
	 */
	public function actionVerifyKey()
	{
		if(isset($_GET['key']) && isset($_GET['vault'])){
			$vaultName = $_GET['vault'].'-local';	// check the key of local vault
			if($model = Vault::model()->findByAttributes(array('name'=>$vaultName))){
				$model->loadKey();
				if($model->key && $model->key == $_GET['key']){
					if($model->state == CREATED){
						$model->state = VERIFIED;
						$model->save();
					}
					echo 1;
					Yii::app()->end();
				}		
			}
		}
		echo 0;
		Yii::app()->end();
	}


	public function actionGetSchedule()
	{
		if(isset($_GET['key']) && isset($_GET['vault'])){
			$vaultName = $_GET['vault'].'-local';	// check the key of local vault
			if($model = Vault::model()->findByAttributes(array('name'=>$vaultName))){
				$model->loadKey();
				if($model->key && $model->key == $_GET['key']){
					echo $model->schedule;
					Yii::app()->end();
				}		
			}
		}
		echo 0;
		Yii::app()->end();
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
		$dataProvider=new CActiveDataProvider('Vault');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Vault('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Vault']))
			$model->attributes=$_GET['Vault'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Vault the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Vault::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		$model->loadKey();
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Vault $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='vault-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}


/*
					// contact remote server
						//$model->state = INITIATED;

					/*
						$data = array('key'=>$model->key, 'host'=>$model->normalizeHost(Yii::app()->getBaseUrl(true)));
						$url = $model->host.'/vault/verifyKey';
						$options = array(
							'http' => array(
								'method'  => 'GET',
								'content' => json_encode( $data ),
								'header'=>  "Content-Type: application/json\r\n" .
											"Accept: application/json\r\n"
							  )
						);
						$context     = stream_context_create($options);
						$result      = file_get_contents($url, false, $context);
						$response    = json_decode($result);
						//file_put_contents('/tmp/verify.txt', $result);
						//var_dump($response);
					*/
/*
					$postdata = http_build_query(
						array('key'=>$model->key, 'host'=>$model->normalizeHost(Yii::app()->getBaseUrl(true)))
					);

					$opts = array('http' =>
						array(
							'method'  => 'POST',
							'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => $postdata
						)
					);
					$context  = stream_context_create($opts);
					$result = file_get_contents($model->host.'/vault/verifyKey', false, $context);


					file_put_contents('/tmp/verify.txt', $response);
*/
