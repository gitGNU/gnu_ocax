<?php

class ConsultaController extends Controller
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
				'actions'=>array('view','index','getConsulta'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','subscribe'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('teamView','edit','update','managed'),
				'expression'=>"Yii::app()->user->isTeamMember()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('adminView','admin','manage','delete'),
				'expression'=>"Yii::app()->user->isManager()",
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
		$this->layout='//layouts/column1';
		$model=$this->loadModel($id);
		$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));

		$this->render('view',array(
			'model'=>$model,
			'respuestas'=>$respuestas,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->layout='//layouts/column1';
		$model=new Consulta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Consulta']))
			$model->attributes=$_GET['Consulta'];

		$this->render('index',array(
			'model'=>$model,
		));
	}


	public function actionGetConsulta($id)
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		$model=$this->loadModel($id);
		if($model){
			$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));
			echo CJavaScript::jsonEncode(array('html'=>$this->renderPartial('view',array('model'=>$model,'respuestas'=>$respuestas),true,true)));
		}else
			echo 0;
	}

	public function actionSubscribe()
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		if(isset($_POST['consulta']))
		{
			$user = Yii::app()->user->getUserID();
			$criteria = new CDbCriteria;
			$criteria->condition = 'consulta = '.$_POST['consulta'].' AND user = '.$user;
			$model=ConsultaSubscribe::model()->find($criteria);
			if($model){
				$model->delete();
				echo '0';
			}else{
				$model=new ConsultaSubscribe;
				$model->consulta = $_POST['consulta'];	// should check if consulta id is valid.
				$model->user = $user;
				$model->save();
				echo '1';
			}			
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->layout='//layouts/column1';
		$model=new Consulta;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Consulta']))
		{
			$model->attributes=$_POST['Consulta'];
			$model->user = Yii::app()->user->getUserID();
			$model->created = date('Y-m-d');
			$model->state = 0;
			if($model->save()){
				$subscription=new ConsultaSubscribe;
				$subscription->user = $model->user;
				$subscription->consulta = $model->id;
				$subscription->save();

				$this->redirect(array('/user/panel'));
			}
		}
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * If save is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionEdit($id)	//team_memeber edits a $model->body and $model->type.
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));
		if(isset($_POST['Consulta']))
		{
			$model->attributes=$_POST['Consulta'];
			if($model->save()){
				Yii::app()->user->setFlash('prompt', "prompt_email");
				$this->redirect(array('teamView','id'=>$model->id));
			}
		}
		$menu=Null;
		if(isset($_GET['menu']) && Yii::app()->user->isTeamMember())
			$menu=$_GET['menu'];
		$this->render('edit',array(
			'model'=>$model,
			'menu'=>$menu,
		));
	}

	/**
	 * View for team_member.
	 */
	public function actionTeamView($id)
	{
		$model=$this->loadModel($id);
		$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));

		$this->render('teamView',array(
			'model'=>$model,
			'respuestas'=>$respuestas,
		));
	}

	/**
	 * Updates a model
	 * All attribs except $body
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));
		if(isset($_POST['Consulta']))
		{
			$model->attributes=$_POST['Consulta'];
			if($model->save()){
				Yii::app()->user->setFlash('prompt', "prompt_email");
				$this->redirect(array('teamView','id'=>$model->id));
			}
		}
		$this->render('update',array(
			'model'=>$model,
			'respuestas'=>$respuestas,
		));
	}

	public function actionManaged()
	{
		// grid of consultas by team_member
		$this->layout='//layouts/column1';

		$model=new Consulta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Consulta']))
			$model->attributes=$_GET['Consulta'];
		$model->team_member = Yii::app()->user->getUserID();

		$this->render('managed',array(
			'model'=>$model,
		));
	}

	public function actionManage($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Consulta']))
		{
			$team_member=$model->team_member;
			$model->attributes=$_POST['Consulta'];
			if($team_member != $model->team_member){
				if($model->team_member){
					$model->assigned=date('Y-m-d');
					if($model->state == 0)	// not working !!!
						$model->state=1;	// recibido por el OCA(x)
				}else{
					$model->assigned=Null;
					$model->state=0;
				}
			}
			if($model->save()){
				$subscription=new ConsultaSubscribe;
				$subscription->user = $model->team_member;
				$subscription->consulta = $model->id;
				$subscription->save();

				Yii::app()->user->setFlash('prompt', "prompt_email");
				$this->redirect(array('adminView','id'=>$model->id));
			}
		}

		$team_members = user::model()->findAll(array("condition"=>"is_team_member =  1","order"=>"username"));
		$this->render('manage',array(
			'model'=>$model,
			'team_members'=>$team_members,
		));
	}


	public function actionAdminView($id)
	{
		$model=$this->loadModel($id);
		$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));
		$this->render('adminView',array(
			'model'=>$model,
			'respuestas'=>$respuestas,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->layout='//layouts/column1';
		$model=new Consulta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Consulta']))
			$model->attributes=$_GET['Consulta'];

		$this->render('admin',array(
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
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Consulta::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='consulta-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
