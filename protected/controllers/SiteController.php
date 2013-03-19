<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				//$headers = "From: {$model->email}\r\nReply-To: {$model->email}";
				$mailer = new Mailer();

				$mailer->AddReplyTo($model->email);
				$mailer->SetFrom($model->email);
				$mailer->AddAddress(Config::model()->findByPk('emailContactAddress')->value);
				$mailer->Subject=$model->subject;
				$mailer->Body=$model->body;

				if($mailer->send())
					Yii::app()->user->setFlash('contact','Thank you for contacting us. We will get back as soon as possible.');
				else
					Yii::app()->user->setFlash('error','Error while sending email<br />"'.$mailer->ErrorInfo.'"');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	private function getActivationEmailText($user)
	{
		return '<p>Please click the link below to activate your account.<br />'.
		'<a href="'.Yii::app()->createAbsoluteUrl('site/activate', array('c' => $user->activationcode)).'">'.
		Yii::app()->createAbsoluteUrl('site/activate', array('c' => $user->activationcode)).'</a></p>';
	}

	public function actionSendActivationCode()
	{
		if(Yii::app()->user->isGuest) // add accessRules() to $this controller instead?
			Yii::app()->end();

		$user=User::model()->findByAttributes(array('username'=>Yii::app()->user->id));
		$user->activationcode = $user->generateActivationCode();
		$user->save();

 		$mailer = new Mailer();
		$mailer->AddAddress($user->email);
		$mailer->SetFrom(Config::model()->findByPk('emailNoReply')->value, Config::model()->findByPk('siglas')->value);

		$mailer->Subject = 'Activate your account';
		$mailer->Body = '<p>Hello '.$user->fullname.',</p>'.$this->getActivationEmailText($user).'<p>Thank you,';
		$mailer->Body = $mailer->Body.'<br />'.Config::model()->findByPk('observatoryName')->value.'</p>'; 
		if($mailer->send())
			Yii::app()->user->setFlash('success','We sent you an email');
		else
			Yii::app()->user->setFlash('newActivationCodeError','Error while sending email<br />"'.$mailer->ErrorInfo.'"');

		$this->redirect(array('/user/panel'));
	}

	public function actionRegister()
	{
 
		$model=new RegisterForm;
		$newUser = new User;
 
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
 
		if(isset($_POST['RegisterForm']))
		{
			$model->attributes=$_POST['RegisterForm'];
 
			$newUser->username = $model->username;
			$newUser->fullname = $model->fullname;
			$newSalt=$newUser->generateSalt();
 			$newUser->password = $newUser->hashPassword($model->password,$newSalt);
			$newUser->salt = $newSalt;
 			$newUser->activationcode = $newUser->generateActivationCode();
			$newUser->is_active = 0;
 			$newUser->username = $model->username;
 			$newUser->email = $model->email;
			$newUser->joined = date('Y-m-d');

			if ($model->validate() && $newUser->save())
			{
				//if want to go login, just uncomment this below
				$identity=new UserIdentity($newUser->username,$model->password);
				//$identity->authenticate();
				Yii::app()->user->login($identity,0);
				//$this->redirect(array('/user/panel'));
 
 				$mailer = new Mailer();
				$mailer->AddAddress($newUser->email);
				$mailer->SetFrom(Config::model()->findByPk('emailNoReply')->value, Config::model()->findByPk('siglas')->value);
				$mailer->Subject = 'Welcome to the '.Config::model()->findByPk('siglas')->value;

				$mailer->Body = '<p>Hello '.$newUser->fullname.',</p><p>Time to audit your council!</p>'.
				$this->getActivationEmailText($newUser).'<p>Thank you,<br />'.
				Config::model()->findByPk('observatoryName')->value.'</p>'; 
 
				if($mailer->send())
					Yii::app()->user->setFlash('success','We sent you an email');
				else
					Yii::app()->user->setFlash('newActivationCodeError','Error while sending email: '.$mailer->ErrorInfo);

				$this->redirect(array('/user/panel'));
			} 
		}
		$this->render('register',array('model'=>$model));
	}
 
	/**
	 * Activation Action
	*/
	public function actionActivate()
	{
		$code = Yii::app()->request->getQuery('c');
		if($code)
		{
			$model = User::model()->findByAttributes(array('activationcode'=>$code));
			if($model){
				$model->is_active=1;
				$model->save();
				Yii::app()->user->setFlash('success','Your account is active');
			}
		}
		if(!Yii::app()->user->isGuest)
			$this->redirect(array('/user/panel'));
		else
			$this->redirect(array('login'));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){
				if(Yii::app()->user->returnUrl != Yii::app()->getHomeUrl())
					Yii::app()->request->redirect(Yii::app()->user->returnUrl);
				else
					$this->redirect(array('user/panel'));
			}
		}

		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
