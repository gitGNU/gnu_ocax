<?php

class EmailController extends Controller
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
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform
				'actions'=>array('contactPetition'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','create'/*,'update'*/),
				'expression'=>"(Yii::app()->user->isManager() || Yii::app()->user->isTeamMember())",	//not working. check this.
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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

	private function getReturnURL($menu_type)
	{
		if($menu_type == 'team')
			return 'consulta/teamView';
		return 'consulta/admin';
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		//if(!Yii::app()->request->isAjaxRequest)
		//	Yii::app()->end();

		$model=new Email;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Email']))
		{
			$returnURL=$_POST['Email']['returnURL'];

			$model->attributes=$_POST['Email'];
			$model->created = date('c');
			$model->sent=0;

			if($model->sender == 0)
				$model->sent_as=Config::model()->findByPk('emailNoReply')->value;
			else
				$model->sent_as=User::model()->findByPk($model->sender)->email;

			$model->sender = Yii::app()->user->getUserID();
			if($model->save()){

 				$mailer = new Mailer();
				$addresses = explode(',', $model->recipients);
				foreach($addresses as $address)
					$mailer->AddBCC(trim($address));

				$mailer->SetFrom($model->sent_as, Config::model()->findByPk('siglas')->value);
				$mailer->Subject=$model->title;
				$mailer->Body=$model->body;

				if($mailer->send()){
					$model->sent=1;
					$model->save();
					$link=CHtml::link(__('View email'),array('email/index/'.$model->consulta.'?menu=manager'));	// need to fix this!!
					Yii::app()->user->setFlash('success',__('Email sent OK').'&nbsp;&nbsp;&nbsp;'.$link);
				}else
					Yii::app()->user->setFlash('error',__('Error while sending email').'<br />"'.$mailer->ErrorInfo.'"');

				$this->redirect(array($returnURL,'id'=>$model->consulta));
			}

		}
		else{	//Get consulta id
			if(isset($_GET['consulta']) && !$model->consulta)
				$model->consulta=$_GET['consulta'];
			if(isset($_GET['menu']))
				$returnURL=$this->getReturnURL($_GET['menu']);
		}
		$model->sender=Yii::app()->user->getUserID();
		$consulta=Consulta::model()->findByPk($model->consulta);
		$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->consulta));

		if(!$model->body)
			$model->body=Emailtext::model()->findByPk($consulta->state)->getBody($consulta);
		if(!$model->title)
			$model->title=$consulta->getHumanStates($consulta->state);

		$this->render('create',array(
			'model'=>$model,
			'returnURL'=>$returnURL,
			'consulta'=>$consulta,
			'respuestas'=>$respuestas,
		));
	}


	public function actionContactPetition($recipient_id=Null, $consulta_id=Null)
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		$model=new Email;

		if(isset($_POST['Email']))
		{
			$model->attributes=$_POST['Email'];

			$recipient = User::model()->findByAttributes(array('email'=>$model->recipients));

			if(BlockUser::model()->findByAttributes(array('user'=>$recipient->id, 'blocked_user'=>Yii::app()->user->getUserID()))){
					echo $recipient->fullname.' '.__('has blocked you');
					Yii::app()->end();
			}

			$model->created = date('c');
			$model->sent=0;
			$model->type=1;
			$model->sent_as = Config::model()->findByPk('emailNoReply')->value;
			$model->body = htmLawed::hl($model->body, array('elements'=>'-*', 'keep_bad'=>0));
			$model->body = nl2br($model->body);
			$model->body = '<p>'.$model->title.'</p><p><i>'.$model->body.'</i></p>';	//get the preamble from the title

			$model->title= __('User request from the').' '.Config::model()->findByPk('siglas')->value;

			if($model->save()){
 				$mailer = new Mailer();
				$mailer->SetFrom($model->sent_as, Config::model()->findByPk('siglas')->value);
				$mailer->AddAddress($model->recipients);
				$mailer->Subject=$model->title;
				$mailer->Body=$model->body;

				if($mailer->send()){
					$model->sent=1;
					$model->save();
					echo 1;
				}else{
					echo $mailer->ErrorInfo.' '.__('Email not sent');
				}
				Yii::app()->end();
			}

			echo 1;
			Yii::app()->end();
		}

		if(isset($_GET['recipient_id']) && isset($_GET['consulta_id'])){
			$model->consulta=$_GET['consulta_id'];
			$recipient = User::model()->findByPk($_GET['recipient_id']);
			echo $this->renderPartial('_contactPetition', array('model'=>$model, 'recipient'=>$recipient, false,true));
		}else
			echo 0;
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
	public function actionIndex($id)
	{

		$consulta=Consulta::model()->findByPk($id);

		$dataProvider=new CActiveDataProvider('Email', array(
			'criteria'=>array('condition'=>'consulta='.$id,'order'=>'created DESC')
		));

		//$dataProvider=new CActiveDataProvider('Email');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'consulta'=>$consulta,
			'menu'=>$_GET['menu'],
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Email('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Email']))
			$model->attributes=$_GET['Email'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Email the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Email::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Email $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='email-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
