<?php
 
/**
 * RegisterForm class.
 * RegisterForm is the data structure for keeping
 * user login form data. It is used by the 'register' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
	public $username;
	public $fullname;
	public $password;
	public $password_repeat;
	public $joined;
	public $activationcode;
	public $activationstatus;
	public $salt;
	public $email;
	public $verifyCode;
 
	private $_identity;
 
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
		// username, password, email are required
		array('username, fullname, password , password_repeat, email', 'required'),
		// username and email should be unique
		array('username, email', 'unique', 'className' => 'User'),
		// email should be in email format
		array('email', 'email'),
		array('password', 'length', 'min' => 6, 
			    'tooShort'=>Yii::t("translation", "{attribute} is too short."),
			    'tooLong'=>Yii::t("translation", "{attribute} is too long.")),
		array('password', 'compare', 'on'=>'registration', 'compareAttribute'=>'password_repeat'),
		array('password_repeat', 'safe'),
		array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}
 
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
		'username'=>'Username',
		'password'=>'Password',
		'password_repeat'=>'Verified Password',
		'email'=>'Email',
		'verifyCode'=>'Verification Code',
		);
	}
 
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	// what?? no it isn't !!!???? Look into this.
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}
}
