<?php

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
				'actions'=>array('panel','update'),
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
		$consultas=new CActiveDataProvider('Consulta', array(
			'criteria'=>array(
				'condition'=>"user=$id",
				'order'=>'created DESC',
			),
			'pagination'=>array(
				'pageSize'=>20,
			),
		));

		$userid=Yii::app()->user->getUserID();
		$subscribed=new CActiveDataProvider('Consulta',array(
			'criteria'=>array(
				'with'=>array('subscriptions'),
				'condition'=>'	subscriptions.consulta = t.id AND
								subscriptions.user = '.$userid.' AND
								t.user != '.$userid.' AND
								( t.team_member != '.$userid.' || t.team_member IS NULL )',
				'together'=>true,
			),
		));

		$this->render('panel',array(
			'model'=>$this->loadModel($user->id),
			'consultas'=>$consultas,
			'subscribed'=>$subscribed,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{

		$consultas=new CActiveDataProvider('Consulta', array(
			'criteria'=>array(
				'condition'=>"user=$id",
				'order'=>'created DESC',
			),
			'pagination'=>array(
				'pageSize'=>20,
			),
		));

		$this->layout='//layouts/column2';
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'consultas'=>$consultas,
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

			if($model->save()){
				Yii::app()->user->setFlash('success', "changes saved");
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
		$this->loadModel($id)->delete();

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
