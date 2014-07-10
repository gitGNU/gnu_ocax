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

#### The vault creation proceedure ####

Andy = I will save Dave's copies on my server
Dave = Andy will save my copies on his server

0.	Andy = I create a local vault because I will save Dave's copies on my server
	vault->create() Andy defines available schedule
	vault->beforeSave() generates key for local vault.
	
0.	Dave = I create a remote vault becuase I want to save my copies on Andy's server

1.	Andy -> Dave. Hey Dave, here is the vault key.
	Andy tells Dave the key. This step is not done via OCAx. It is via email or telf.
	
2.	Dave. configureKey
	Dave calls Andy's vault/verifyKey

	// Key exchange completed //

3.	Dave selectes backup schedule
	Dave calls Andy's vault/getSchedule
	Dave calls Andy's vault/setSchedule


#### The backup transfer proceedure #####

Andy = I will save Dave's copies on my server
Dave = Andy will save my copies on his server

0.	Andy runVaultSchedule();

1.	Andy -> Dave Have you got your dump ready?
	Andy calls Dave's vault/remoteWaitingToStartCopyingBackup
	
2.	Dave -> Andy Yes. start copying.
	Dave calls Andy's vault/startCopyingBackup
	
3.	Andy -> Dave Ok. Give me the file
	Andy calls Dave's vault/startTransfer

4.	Andy -> Dave Ok. We've finished copying.
	Andy calls Dave's vault/transferComplete

 */

class VaultController extends Controller
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
				'actions'=>array(	'verifyKey', 'getSchedule', 'setSchedule',
									'remoteWaitingToStartCopyingBackup',
									'startCopyingBackup',
									'startTransfer',
									'transferComplete',
								),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(	'view', 'viewSchedule', 'admin', /*'index',*/
									'create',
									'configureKey', 'configureSchedule',
									'delete'),
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
	 * 
	 * Show all configured Vault schedules
	 */
	public function actionViewSchedule()
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

// ####
// #### The vault creation proceedure ####
// ####


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
			//file_put_contents('/tmp/sch.txt', '---'.$model->schedule.'---');
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
	public function actionConfigureKey($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Vault']))
		{
			$model->attributes=$_POST['Vault'];
			if($model->state == CREATED && $model->key){
				$model->setScenario('newKey');
				if($model->validate()){
					if($model->type == REMOTE && $model->state < VERIFIED){
						$vaultName = $model->host2VaultName(Yii::app()->getBaseUrl(true),0);
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

	public function actionConfigureSchedule($id)
	{
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['Vault']))
		{
			$model->attributes=$_POST['Vault'];
			if($model->type == REMOTE && $model->state == VERIFIED){
				$vaultName = $model->host2VaultName(Yii::app()->getBaseUrl(true),0);
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
		if($model = Vault::model()->findByIncomingCreds()){
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
		echo 0;
		Yii::app()->end();
	}

// ####
// #### The backup transfer proceedure #####
// ####

	
	public function actionRemoteWaitingToStartCopyingBackup()
	{

		if($model = Vault::model()->findByIncomingCreds(REMOTE)){
			if($model->state == READY){
				// Don't start another backup if we've already created one today.
				if(Backup::model()->findByDay(date('Y-m-d'), $model->id )){
					echo 0;
					Yii::app()->end();
				}
				$backup = new Backup;
				$backup->vault = $model->id;
				$backup->created = date('c');
				//save it now because buildBackupFile() can take time and we don't want do to run it twice.
				$backup->save();
					
				if($backup->buildBackupFile()){
					$backup->filesize = filesize($model->getVaultDir().$backup->filename);	
					$backup->save();	
					$model->state = LOADED;
					$model->save();
				}else
					$backup->delete();

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

	public function actionStartCopyingBackup()
	{
		if($model = Vault::model()->findByIncomingCreds()){
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
				/*
				$copy = @file_get_contents($model->host.'/vault/startTransfer'.
														'?key='.$model->key.
														'&vault='.$vaultName,
														false,
														$model->getStreamContext(3)
						);
				*/
				$source = $model->host.'/vault/startTransfer'.
										'?key='.$model->key.
										'&vault='.$vaultName;
							
				$dest = $model->getVaultDir().$backup->filename;
				copy($source, $dest);

				$backup->completed = date('c');
				$backup->save();
				$model->state = READY;
				$model->save();
					
				$backup->state = 0;	// failed
					
				if($backup->filesize = filesize($model->getVaultDir().$backup->filename)){
					$vaultName = $model->host2VaultName(Yii::app()->getBaseUrl(true), 0);
					$confirmation = 'nada';
					$confirmation = @file_get_contents($model->host.'/vault/transferComplete'.
											'?key='.$model->key.
											'&vault='.$vaultName.
											'&filesize='.$backup->filesize,
											false,
											$model->getStreamContext(3)
								);
					if($confirmation == 1)
						$backup->state = 1; // success!!
				}
				$backup->save();
			}
		}
	}

	public function actionStartTransfer()
	{
		if($model = Vault::model()->findByIncomingCreds(REMOTE)){
			
			if($backup = Backup::model()->findByDay(date('Y-m-d'), $model->id )){
				if($backup->initiated){
					echo 0;
					Yii::app()->end();
				}
				$backup->initiated = date('c');
				$backup->save();
				$model->state = BUSY;
				$model->save();

				$backup->download();
			}
		}
		echo 0;
		Yii::app()->end();
	}

	public function actionTransferComplete()
	{
		if($model = Vault::model()->findByIncomingCreds(REMOTE)){
			if($backup = Backup::model()->findByDay(date('Y-m-d'), $model->id )){
				$model->state = READY;
				$model->save();
					
				if(isset($_GET['filesize']) && $_GET['filesize'] == $backup->filesize)
					$backup->state=1;
				else
					$backup->state=0;

				$backup->completed = date('c');
				$backup->save();
					
				if($model->type == REMOTE)	// condition shouldn't be necessary
					unlink($model->getVaultDir().$backup->filename);
						
				echo $backup->state;
				Yii::app()->end();
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
		$this->redirect(array('/backup/admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Vault('search');
		$model->unsetAttributes();  // clear any default values
		
		$localVaults=new CActiveDataProvider('Vault', array(
							'criteria'=>array('condition'=>"type=0")
						));
		$remoteVaults=new CActiveDataProvider('Vault', array(
							'criteria'=>array('condition'=>"type=1")
						));
				
		//if(isset($_GET['Backup']))
		//	$model->attributes=$_GET['Backup'];

		$this->render('admin',array(
			'model'=>$model, 'localVaults'=>$localVaults, 'remoteVaults'=>$remoteVaults
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
