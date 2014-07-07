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


/**

R = I will save L's copies on my server
L = R will save my copies on his server

0. R runVaultSchedule();
1.	R -> L Have you got your dump ready?
	R calls L's vault/remoteWaitingToStartCopyingBackup
	
2.	L -> R Yes. start copying.
	L calls R's vault/startCopyingBackup
	
3.	R -> L Ok. Give me the file
	R calls L's vault/startTransfer

4.	R -> L Ok. We've finished copying
	R calls L's vault/transferComplete

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
				'actions'=>array('verifyKey', 'getSchedule', 'setSchedule',
								'remoteWaitingToStartCopyingBackup',
								'startCopyingBackup',
								'startTransfer',
								'transferComplete',
								),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(	'view', 'admin', 'index', 'schedule', 'create',
									'update', 'configureSchedule', 'delete'),
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
			$model->state=CREATED;
			if($model->type == REMOTE)
				$model->schedule='0000000';
			if($model->save()){
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

		if(isset($_POST['Vault']))
		{
			$model->attributes=$_POST['Vault'];
			if($model->state == CREATED && $model->key){
				$model->setScenario('newKey');
				if($model->validate()){
					if($model->type == REMOTE && $model->state < VERIFIED){
						$vaultName = rtrim($model->host2VaultName(Yii::app()->getBaseUrl(true)), '-remote');
						$reply=Null;
						$reply = @file_get_contents($model->host.'/vault/verifyKey'.
																	'?key='.$model->key.
																	'&vault='.$vaultName,
																	false,
																	$model->getStreamContext(3)
													);
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

	public function actionConfigureSchedule($id)
	{
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Vault']))
		{
			$model->attributes=$_POST['Vault'];
			if($model->type == REMOTE && $model->state == VERIFIED){
				$vaultName = rtrim($model->host2VaultName(Yii::app()->getBaseUrl(true)), '-remote');
				$reply = Null;
				$reply = @file_get_contents($model->host.'/vault/setSchedule'.
															'?key='.$model->key.
															'&vault='.$vaultName.
															'&schedule='.$model->schedule,
															false,
															$model->getStreamContext()
											);
				if($reply == 1){
					$model->state = READY;
					$model->save();
					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}
		$this->render('view',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 
	 * Show all configured Vault schedules
	 */
	public function actionSchedule()
	{
		$localVaults = Vault::model()->findAllByAttributes(array('type'=>LOCAL, 'state'=>READY));
		$remoteVaults = Vault::model()->findAllByAttributes(array('type'=>REMOTE, 'state'=>READY));
		if(Yii::app()->request->isAjaxRequest){
			$layout='//layouts/column1';
			echo $this->renderPartial('schedule',array(
												'localVaults' =>$localVaults,
												'remoteVaults'=>$remoteVaults
												),
										true,false);
		}else{
			$this->render('schedule',array(
								'localVaults' =>$localVaults,
								'remoteVaults'=>$remoteVaults,
						));
		}
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

	/**
	 * Part of the vault handshake
	 * Remote ocax instalation calls this
	 */
	public function actionGetSchedule()
	{
		if(isset($_GET['key']) && isset($_GET['vault'])){
			$vaultName = $_GET['vault'].'-local';	// check the key of local vault
			if($model = Vault::model()->findByAttributes(array('name'=>$vaultName))){
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
	 * Part of the vault handshake
	 * Remote ocax instalation calls this
	 */
	public function actionSetSchedule()
	{
		if(isset($_GET['vault']) && isset($_GET['key'])){
			if($model = Vault::model()->findByIncomingCreds($_GET['vault'], $_GET['key'])){
				if($model->state >= READY){
					echo 0;
					Yii::app()->end();	
				}
				$model->schedule = $_GET['schedule'];
				$model->state = READY;
				if($model->save()){
					echo 1;
					Yii::app()->end();
				}
			}	
		}
		echo 0;
		Yii::app()->end();
	}


	public function actionRemoteWaitingToStartCopyingBackup()
	{
		if(isset($_GET['vault']) && isset($_GET['key'])){
			if($model = Vault::model()->findByIncomingCreds($_GET['vault'], $_GET['key'], REMOTE)){
				if($model->state == READY){
					// Don't start another backup if we've already created one today.
					if(Backup::model()->findByDay(date('Y-m-d'), $model->id )){
						echo 0;
						Yii::app()->end();
					}
					$backup = new Backup;
					$backup->vault = $model->id;
					//$backup->filename = '/tmp/sitedump.txt';
					if($backup->buildBackupFile()){
						$backup->filesize = filesize($backup->filename);
						$backup->created = date('c');
						
						$model->state = LOADED;
						$model->save();
					}
					echo 0;
					Yii::app()->end();
				}
				// LOADED	We've got the backup file ready for copying.
				// BUSY		Maybe remote host didn't recieve /vault/StartCopyingBackup. Let's send it again.
				if($model->state == LOADED || $model->state == BUSY){
					if($backup = Backup::model()->findByDay(date('Y-m-d'), $model->id )){
						$vaultName = $model->host2VaultName(Yii::app()->getBaseUrl(true), 0);
						@file_get_contents($model->host.'/vault/startCopyingBackup'.
														'?key='.$model->key.
														'&vault='.$vaultName.
														'&filename='.$backup->filename,
														false,
														$model->getStreamContext()
											);
					}
					echo 0;
					Yii::app()->end();
				}
			}
		}
	}

	public function actionStartCopyingBackup()
	{
		if(isset($_GET['vault']) && isset($_GET['key'])){
			if($model = Vault::model()->findByIncomingCreds($_GET['vault'], $_GET['key'])){
				if(Backup::model()->findByDay(date('Y-m-d'), $model->id )){
					echo 0;
					Yii::app()->end();						
				}
				$backup = new Backup;
				$backup->vault = $model->id;
				$backup->filename = $_GET['filename'];
				$backup->created = date('c');
				$backup->initiated = date('c');
				if($backup->save()){
					$model->state = BUSY;
					$model->save();
					$vaultName = $model->host2VaultName(Yii::app()->getBaseUrl(true), 0);
					@file_get_contents($model->host.'/vault/startTransfer'.
								'?key='.$model->key.
								'&vault='.$vaultName.
								false,
								$model->getStreamContext()
					);
					
					//$backup->filesize = filesize($backup->filename);
					$backup->completed = date('c');
					if($backup->save()){
						$model->state = READY;
						$model->save();
						$vaultName = $model->host2VaultName(Yii::app()->getBaseUrl(true), 0);
						@file_get_contents($model->host.'/vault/transferComplete'.
									'?key='.$model->key.
									'&vault='.$vaultName.
									false,
									$model->getStreamContext()
						);									
					}
				}
			}
		}
	}

	public function actionStartTransfer()
	{
		if(isset($_GET['vault']) && isset($_GET['key'])){
			if($model = Vault::model()->findByIncomingCreds($_GET['vault'], $_GET['key']), REMOTE){
				if($backup = Backup::model()->findByDay(date('Y-m-d'), $model->id )){
					if($backup->initiated){
						echo 0;
						Yii::app()->end();						
					}
					$backup->initiated = date('c');
					if($backup->save()){
						$model->state = BUSY;
						$model->save();

						header("Pragma: public");
						header("Expires: 0");
						header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
						header("Cache-Control: public");
						header("Content-Description: File Transfer");
						header("Content-type: application/octet-stream");
						header("Content-Disposition: attachment; filename=\"".$file."\"");
						header("Content-Transfer-Encoding: binary");
						header("Content-Length: ".$backup->filesize);
						ob_end_flush();
						@readfile($path.$file);
						exit;
					}
				}
			}
		}
	}

	public function actionTransferComplete()
	{
		if(isset($_GET['vault']) && isset($_GET['key'])){
			if($model = Vault::model()->findByIncomingCreds($_GET['vault'], $_GET['key']), REMOTE){
				if($backup = Backup::model()->findByDay(date('Y-m-d'), $model->id )){
					$backup->completed = date('c');
					$model->state = READY;
					$model->save();
				}
			}
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
		$this->redirect(array('/backup/admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->redirect(array('/backup/admin'));
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
