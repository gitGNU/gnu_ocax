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
			'postOnly + delete, megaDelete', // we only allow deletion via POST request
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
				'actions'=>array('create','edit','subscribe','delete'),
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('teamView','update','managed'),
				'expression'=>"Yii::app()->user->isTeamMember()",
			),
			array('allow',
				'actions'=>array('adminView','admin','manage'),
				'expression'=>"Yii::app()->user->isManager()",
			),
			array('allow',
				'actions'=>array('getConsultaForTeam','megaDelete'),
				'expression'=>"Yii::app()->user->isManager() || Yii::app()->user->isAdmin()",
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

	public function actionGetConsultaForTeam($id)
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		$model=$this->loadModel($id);
		if($model){
			$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));
			echo CJavaScript::jsonEncode(array('html'=>$this->renderPartial('_teamView',array('model'=>$model,'respuestas'=>$respuestas),true,true)));
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

		if(isset($_GET['budget'])){
			//$budget=Budget::model()->findByPk($_GET['budget']);
			$model->budget=$_GET['budget'];
			$model->type = 1;
		}

		if(isset($_POST['Consulta']))
		{
			$model->attributes=$_POST['Consulta'];
			$model->user = Yii::app()->user->getUserID();
			$model->created = date('Y-m-d');
			$model->state = 0;
			$model->title = htmLawed::hl($model->title, array('elements'=>'-*', 'keep_bad'=>0));
			$model->body = htmLawed::hl($model->body, array('safe'=>1, 'deny_attribute'=>'script, style, class, id'));
			if($model->save()){
				$subscription=new ConsultaSubscribe;
				$subscription->user = $model->user;
				$subscription->consulta = $model->id;
				$subscription->save();

 				$mailer = new Mailer();

				$mailer->AddAddress($model->user0->email);
				$recipients=$model->user0->email.',';
				$managers=User::model()->findAllByAttributes(array('is_manager'=>'1'));
				foreach($managers as $manager){
					$mailer->AddBCC($manager->email);
					$recipients=$recipients.' '.$manager->email.',';
				}
				$recipients = substr_replace($recipients ,'',-1);

				$mailer->SetFrom(Config::model()->findByPk('emailNoReply')->value, Config::model()->findByPk('siglas')->value);
				$mailer->Subject=$model->getHumanStates($model->state);
				$mailer->Body=Emailtext::model()->findByPk($model->state)->getBody($model);

				$email = new Email;

				$email->created = date('c');
				$email->sender=Null;	//app generated email
				$email->sent_as=Config::model()->findByPk('emailNoReply')->value;
				$email->title=$mailer->Subject;
				$email->body=$mailer->Body;
				$email->recipients=$recipients;
				$email->consulta=$model->id;

				if($mailer->send()){
					$email->sent=1;
					$email->save();
					Yii::app()->user->setFlash('success', 'Consulta has been published<br/>We have sent you an email');
				}else{
					$email->sent=0;
					$email->save();
					Yii::app()->user->setFlash('success', 'Consulta has been published');
				}
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

		$userid= Yii::app()->user->getUserID();
		if( !($model->team_member == $userid || ($model->state==0 && $model->user == $userid) )){
			$this->render('/site/index');
			Yii::app()->end();
		}

		$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));
		if(isset($_POST['Consulta']))
		{
			$model->attributes=$_POST['Consulta'];
			$model->title = htmLawed::hl($model->title, array('elements'=>'-*', 'keep_bad'=>0));
			$model->body = htmLawed::hl($model->body, array('safe'=>1, 'deny_attribute'=>'script, style, class, id'));
			if($model->save()){
				if(Yii::app()->user->getUserID() == $model->team_member){
					$model->promptEmail();
					$this->redirect(array('teamView','id'=>$model->id));
				}else{
					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}
		$menu=Null;
		if(isset($_GET['menu']) && ($userid == $model->team_member))
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
				$model->promptEmail();
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
			if($model->save() && $model->team_member){
				if(!ConsultaSubscribe::model()->find(array('condition'=>'consulta='.$model->id. ' AND user='.$model->team_member))){
					$subscription=new ConsultaSubscribe;
					$subscription->user = $model->team_member;
					$subscription->consulta = $model->id;
					$subscription->save();
				}
				$model->promptEmail();
				$team_members = user::model()->findAll(array("condition"=>"is_team_member =  1","order"=>"username"));
				//$this->redirect(array('manage','id'=>$model->id,'team_members'=>$team_members,));

				//can we use this instead of render? $this->refresh();
				$this->render('manage',array(
					'model'=>$model,
					'team_members'=>$team_members,
				));
				Yii::app()->end();
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
		$model = $this->loadModel($id);
		$user=Yii::app()->user->getUserID();
		if($model->state==0 && ($model->user == $user || Yii::app()->user->isManager()) ){
			//$criteria = new CDbCriteria;
			//$criteria->condition = 'consulta = '.$model->id.' AND user = '.$user;
			$subscription=ConsultaSubscribe::model()->findByAttributes(array('consulta'=>$model->id));
			if($subscription)
				$subscription->delete();

			$email = Email::model()->findByAttributes(array('consulta'=>$model->id));
			if($email)
				$email->delete();

			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}else
			$this->redirect(array('/site/index'));
	}

	/**
	 * Deletes a consulta and all references.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionMegaDelete($id)
	{
		$model = $this->loadModel($id);

		$subscriptions = ConsultaSubscribe::model()->findAllByAttributes(array('consulta'=>$model->id));
		foreach($subscriptions as $subscription)
			$subscription->delete();

		$emails = Email::model()->findAllByAttributes(array('consulta'=>$model->id));
		foreach($emails as $email)
			$email->delete();

		$comments = Comment::model()->findAllByAttributes(array('consulta'=>$model->id));
		foreach($comments as $comment)
			$comment->delete();

		$respuestas = Respuesta::model()->findAllByAttributes(array('consulta'=>$model->id));
		foreach($respuestas as $respuesta){

			$votes = Vote::model()->findAllByAttributes(array('respuesta'=>$respuesta->id));
			foreach($votes as $vote)
				$vote->delete();

			$comments = Comment::model()->findAllByAttributes(array('respuesta'=>$respuesta->id));
			foreach($comments as $comment)
				$comment->delete();

			$respuesta->delete();
		}
		$model->delete();
		echo $id;
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
