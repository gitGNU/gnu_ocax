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
		array('username, fullname, password , password_repeat, email, verifyCode', 'required'),
		// username and email should be unique
		array('username, email', 'unique', 'className' => 'User'),
		array('username', 'validateUsername'),
		// email should be in email format
		array('email', 'email'),
		array('password', 'length', 'min' => 6, 
			    'tooShort'=>Yii::t("translation", "{attribute} es muy corta (6 carácteres min)."),
			    'tooLong'=>Yii::t("translation", "{attribute} is too long.")),
		array('password_repeat', 'compare', 'compareAttribute'=>'password'),
		array('password_repeat', 'safe'),
		array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}
 
    public function validateUsername()
    {
		if (strlen($this->username) < 4){
			return;
		}
		if (strlen($this->username) > 32){
			$this->addError('username','Username too long. Max 32 characters');
			return;
		}
        if (!preg_match('/^[A-Za-z0-9_]+$/', $this->username))
            $this->addError('username','Only characters a-z A-Z and 0-9 are allowed.');
    }

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
		'username'=> __('Username'),
		'fullname'=> __('Full name'),
		'password'=> __('Password'),
		'password_repeat'=> __('Repeat password'),
		'email'=> __('Email'),
		'verifyCode'=>'Captcha',
		);
	}
 
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	// what?? no it isn't !!!???? Look into this.
/*
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Usuario o constraseña incorrecta.');
		}
	}
*/
}
