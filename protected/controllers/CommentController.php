<?php

class CommentController extends Controller
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
				'actions'=>array('getForm', 'create','delete'/*,'update'*/),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionGetForm($comment_on, $id)
	{
		//if(!Yii::app()->request->isAjaxRequest)
			//Yii::app()->end();

		$model = new Comment;
		if($comment_on == 'consulta')
			$model->consulta = $id;
		if($comment_on == 'respuesta')
			$model->respuesta = $id;
		
		$user=User::model()->findByPk(Yii::app()->user->getUserID());
		echo CJavaScript::jsonEncode(array(
						'html'=>$this->renderPartial('_form',array('model'=>$model,'fullname'=>$user->fullname),true,true)));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		$model=new Comment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Comment']))
		{
			$model->attributes=$_POST['Comment'];
			$model->body = htmLawed::hl($model->body, array('elements'=>'-*', 'keep_bad'=>0));
			$model->body = nl2br($model->body);
			$model->user=Yii::app()->user->getUserID();
			$model->created=date('c');
			if($model->save()){

				echo CJavaScript::jsonEncode(array(
					'html'=>$this->renderPartial('_view',array('data'=>$model),true,true),
				));

 				$mailer = new Mailer();

				if($model->consulta == Null)
					$consulta = Consulta::model()->findByPk($model->respuesta0->consulta);
				else
					$consulta = Consulta::model()->findByPk($model->consulta);

				$criteria = array(
					'with'=>array('consultaSubscribes'),
					'condition'=>' consultaSubscribes.consulta = '.$consulta->id,
					'together'=>true,
				);
				$subscribedUsers = User::model()->findAll($criteria);

				foreach($subscribedUsers as $subscribed)
					$mailer->AddBCC($subscribed->email);

				$mailer->SetFrom(Config::model()->findByPk('emailNoReply')->value, Config::model()->findByPk('siglas')->value);
				$mailer->Subject='New comment at: '.$consulta->title;

				$mailer->Body='	<p>A new comment has been added to the consulta "'.$consulta->title.'"<br />
								<a href="'.Yii::app()->createAbsoluteUrl('consulta/view', array('id' => $consulta->id)).'">'.
								Yii::app()->createAbsoluteUrl('consulta/view', array('id' => $consulta->id)).'</a></p><p><i>'.
								$model->body.'</i></p><p>Kind regards,<br />'.
								Config::model()->findByPk('observatoryName')->value.'</p>';
				$mailer->send();
			}else
				echo 0;
		}else
			echo 0;
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

		if(isset($_POST['Comment']))
		{
			$model->attributes=$_POST['Comment'];
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
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		$model=$this->loadModel($id);
		if($model->user == Yii::app()->user->getUserID()){
			$model->delete();
			echo 1;
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser

			//if(!isset($_GET['ajax']))
			//	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Comment');

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Comment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Comment']))
			$model->attributes=$_GET['Comment'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Comment the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Comment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Comment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
