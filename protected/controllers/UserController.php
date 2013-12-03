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

class UserController extends Controller
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
/*
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
*/
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('panel','update','block'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('view','admin','delete','updateRoles'),
				'expression'=>"Yii::app()->user->isAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * User's panel.
	 */
	public function actionPanel()
	{
		$user=User::model()->findByAttributes(array('username'=>Yii::app()->user->id));

		$id=Yii::app()->user->getUserID();
		$enquirys=new CActiveDataProvider('Enquiry', array(
			'criteria'=>array(
				'condition'=>"user=$id",
			),
			'sort'=>array('defaultOrder'=>'modified DESC'),
		));

		$userid=Yii::app()->user->getUserID();
		$subscribed=new CActiveDataProvider('Enquiry',array(
			'criteria'=>array(
				'with'=>array('subscriptions'),
				'condition'=>'	subscriptions.enquiry = t.id AND
								subscriptions.user = '.$userid.' AND
								t.user != '.$userid.' AND
								( t.team_member != '.$userid.' || t.team_member IS NULL )',
				'together'=>true,
				//'order'=>'t.id DESC',
			),
			'sort'=>array('defaultOrder'=>'t.modified DESC'),
		));

		// check for OCAx updates once a week
		$upgrade = Null;
		if(Yii::app()->user->isAdmin()){
			$latest_version_file = Yii::app()->basePath.'/runtime/latest.ocax.version';
			if (file_exists($latest_version_file)) {
				$date = new DateTime();
	
				if( $date->getTimestamp() - filemtime($latest_version_file) > 604800 ){ //604800 a week
					$context = stream_context_create(array(
						'http' => array(
						'header' => 'Content-type: application/x-www-form-urlencoded',
						'method' => 'GET',
						'timeout' => 5
					)));
					if($result = @file_get_contents('http://ocax.net/network/current/version', 0, $context)){
						$new_version = json_decode($result);
						if(isset($new_version->ocax))
							file_put_contents($latest_version_file, $new_version->ocax);
					}
				}
			}else
				copy(Yii::app()->basePath.'/data/ocax.version', Yii::app()->basePath.'/runtime/latest.ocax.version');

			$installed_version = getOCAXVersion();
			$installed_version = str_replace('.','',$installed_version );
			$installed_version = str_pad($installed_version, 10 , '0');			
			
			$latest_version = file_get_contents($latest_version_file);
			$latest_version = str_replace('.','',$latest_version );
			$latest_version = str_pad($latest_version, 10 , '0');
			
			if($latest_version > $installed_version)
				$upgrade = file_get_contents($latest_version_file);
		}


		$this->render('panel',array(
			'model'=>$this->loadModel($user->id),
			'enquirys'=>$enquirys,
			'subscribed'=>$subscribed,
			'upgrade'=>$upgrade,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{

		$enquirys=new CActiveDataProvider('Enquiry', array(
			'criteria'=>array(
				'condition'=>"user=$id",
				'order'=>'created ASC',
			),
			'pagination'=>array(
				'pageSize'=>20,
			),
		));

		$this->layout='//layouts/column2';
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'enquirys'=>$enquirys,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$model=User::model()->findByAttributes(array('username'=>Yii::app()->user->id));
		$email = $model->email;
		$language = $model->language;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		//$model->scenario = 'update';
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];

			if($_POST['User']['new_password'] || $_POST['User']['password_repeat']){

				$model->new_password=$_POST['User']['new_password'];
				$model->password_repeat=$_POST['User']['password_repeat'];
				$model->scenario = 'change_password';

				if(!$model->validate()){
					$this->render('update',array('model'=>$model,));
					yii:app()->end();
				}
				$model->salt=$model->generateSalt();
				$model->password = $model->hashPassword($model->new_password,$model->salt);
			}
			if($email != $model->email)
				$model->is_active=0;

			//if($language != $model->language){
				Yii::app()->language = $model->language;
				$cookie = new CHttpCookie('lang', $model->language);
				$cookie->expire = time()+60*60*24*180; 
				Yii::app()->request->cookies['lang'] = $cookie;
			//}

			if($model->save()){
				Yii::app()->user->setFlash('success', __('Changes saved Ok'));
				if(!$model->is_active)
					$this->redirect(array('/site/sendActivationCode'));
				else
					$this->redirect(array('panel'));
			}
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionBlock($id)
	{
		$blocked_user=User::model()->findByAttributes(array('username'=>$id));
		if(!$blocked_user)
			$this->redirect(array('panel'));
		if($blocked_user->username != Yii::app()->user->id){
			$userid=Yii::app()->user->getUserID();
			if(isset($_GET['confirmed'])){
				$block = new BlockUser;
				if(! $block->findByAttributes(array('user'=>$userid, 'blocked_user'=>$blocked_user->id))){
	
					$block->user=$userid;
					$block->blocked_user=$blocked_user->id;
					$block->save();
				}
				Yii::app()->user->setFlash('success', $blocked_user->fullname.' '.__('is blocked'));
			}else{
				if(! BlockUser::model()->findByAttributes(array('user'=>$userid, 'blocked_user'=>$blocked_user->id)))
					Yii::app()->user->setFlash('prompt_blockuser', $blocked_user->fullname.'|'.$id);
			}
		}
		$this->redirect(array('panel'));
	}

	public function actionUpdateRoles($id)
	{
		$this->layout='//layouts/column2';
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('updateRoles',array(
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
		$model=$this->loadModel($id);

		foreach($model->comments as $comment)
			$comment->delete();
		foreach($model->votes as $vote)
			$vote->delete();
		foreach($model->enquirySubscribes as $subscription)
			$subscription->delete();
		foreach($model->resetPasswords as $resetPassword)
			$resetPassword->delete();
			
		$model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

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
		$model=User::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
