<?php

class BulkEmailController extends Controller
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
			array('allow',
				'actions'=>array('view','create','send','update','admin','showRecipients','delete'),
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
		$model=$this->loadModel($id);
		if($model->sent == 0){
	        $sql = "SELECT id FROM user WHERE is_active = 1";
	        $cnt = "SELECT COUNT(*) FROM ($sql) subq";
			$count = Yii::app()->db->createCommand($cnt)->queryScalar();
		}else{
			$count=count(explode(',',$model->recipients));
		}


		$this->render('view',array(
			'model'=>$model,
			'total_recipients'=>$count,
		));
	}

	public function actionShowRecipients($id=Null)
	{
		$model=Null;
		if($id)
			$model=$this->loadModel($id);
		if($model && $model->sent != 0)
			echo $this->renderPartial('showRecipients',array('recipients'=>$model->recipients,'draft'=>Null),false,true);
		else{
			$users = Yii::app()->db->createCommand()
					->select('email')
					->from('user')
					->where('is_active = 1')
					->queryAll();

			$result='';
			foreach($users as $recipient)
			    $result=$result.$recipient['email'].', ';
			echo $this->renderPartial('showRecipients',array('recipients'=>substr_replace($result ,"",-2),'draft'=>1),false,true);
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new BulkEmail;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BulkEmail']))
		{
			$model->attributes=$_POST['BulkEmail'];

			$model->created = date('c');
			$model->sent=0;
			$model->sender=Yii::app()->user->getUserID();

			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

        $sql = "SELECT id FROM user WHERE is_active = 1";
        $cnt = "SELECT COUNT(*) FROM ($sql) subq";
		$count = Yii::app()->db->createCommand($cnt)->queryScalar();  

		$this->render('create',array(
			'model'=>$model,
			'total_recipients'=>$count,
		));
	}

	/**
	 * Preview email
	 */
	public function actionSend($id)
	{
		$model=$this->loadModel($id);
		$model->setScenario('send');

 		$mailer = new Mailer();

		$users = Yii::app()->db->createCommand()
				->select('email')
				->from('user')
				->where('is_active = 1')
				->queryAll();

		foreach($users as $recipient){
		    $model->recipients=$model->recipients.$recipient['email'].', ';
			$mailer->AddBCC(trim($recipient['email']));
		}
		$model->recipients = substr_replace($model->recipients ,"",-2);
		$model->sender=Yii::app()->user->getUserID();
		$mailer->SetFrom($model->sent_as, Config::model()->findByPk('siglas')->value);
		$mailer->Subject=$model->subject;
		$mailer->Body=$model->body;

		if($mailer->send()){
			$model->sent=2;
			Yii::app()->user->setFlash('success',__('Email sent OK'));
		}else{
			$model->sent=1;
			Yii::app()->user->setFlash('error',__('Error while sending email').'<br />"'.$mailer->ErrorInfo.'"');
		}
		$model->save();
		$this->redirect(array('view','id'=>$model->id));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new BulkEmail('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BulkEmail']))
			$model->attributes=$_GET['BulkEmail'];

		$this->render('admin',array(
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

		if(isset($_POST['BulkEmail']))
		{
			$model->attributes=$_POST['BulkEmail'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

        $sql = "SELECT id FROM user WHERE is_active = 1";
        $cnt = "SELECT COUNT(*) FROM ($sql) subq";
		$count = Yii::app()->db->createCommand($cnt)->queryScalar();  

		$this->render('update',array(
			'model'=>$model,
			'total_recipients'=>$count,
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
		if($model->sent == 0)	// admin can delete a draft
			$model->delete();

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
		$dataProvider=new CActiveDataProvider('BulkEmail');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
*/


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BulkEmail the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BulkEmail::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BulkEmail $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='bulk-email-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
