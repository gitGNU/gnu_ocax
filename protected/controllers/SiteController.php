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
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

//***************** from example start
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
 
		// collect user input data
		if(isset($_POST['RegisterForm']))
		{
			$model->attributes=$_POST['RegisterForm'];
 
			// if ($model->validate()) {
			$newUser->username = $model->username;
			$newUser->fullname = $model->fullname;
			$newSalt=$newUser->generateSalt();
 
			$newUser->password = $newUser->hashPassword($model->password,$newSalt);
			$newUser->salt = $newSalt;
 
			$newUser->activationcode = $newUser->generateActivationCode($model->email);
			// $newUser->activationcode = sha1(mt_rand(10000, 99999).time().$email);
			$newUser->activationstatus = 0;
 
			//return $this->hashPassword($password,$this->salt)===$this->password;
			$newUser->username = $model->username;
 
			$newUser->email = $model->email;
			$newUser->joined = date('Y-m-d');
 
			if( ! $newUser->validate()){
				$errors = $newUser->getErrors();
				print_r($errors);
				Yii::app()->end();
			}


			if ($model->validate() && $newUser->save())
			{
				//if want to go login, just uncomment this below
				$identity=new UserIdentity($newUser->username,$model->password);
				//$identity->authenticate();
				Yii::app()->user->login($identity,0);
				$this->redirect(array('/user/panel'));
 
				//email activation code starts-----------------------------------------
 
				$to = $model->email;
				$subject = "Welcome To GhazaliTajuddin.com!";
				$message = "Thank you for joining!, we have sent you a separate email that contains your activation link";
				$from = "FROM: mr.ghazali@gmail.com";
 
				mail($to,$subject,$message,$from);
 
				//echo $to.$subject.$message.$from;
 
				$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: Mr. Ghazali < mr.ghazali@gmail.com>' . "\r\n";
 
				$subject2 = "Your Activation Link";
 
				$message2 = "<html><body>Please click this below to activate your membership<br />".
				Yii::app()->createAbsoluteUrl('site/activate', array('email' => $newUser->email)).
				"
 
				Thanks you.
				". sha1(mt_rand(10000, 99999).time().$email) ."
				</body></html>";
 
				mail($to, $subject2, $message2, $headers);
				//email activation code end-----------------------------------------
 
				//$this->redirect(Yii::app()->user->returnUrl);
				$this->redirect('/user/panel');
			} 
		}
		// display the register form
		$this->render('register',array('model'=>$model));
	}
 
	/**
	 * Activation Action
	*/
	public function actionActivate()
	{
		$email = Yii::app()->request->getQuery('email');
		// collect user input data
		if(isset($email))
		{
			$model = User::model()->find('email=:email', array(':email'=>$email));
 
			if($email == $model->email){
				$model->activationstatus=1;
				$model->validate();
				$model->save();
			}
		}
		// display the login form
		$this->render('activate',array('model'=>$model));
	}
//***************** from example end

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
			if($model->validate() && $model->login())
				$this->redirect(array('user/panel'));
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
