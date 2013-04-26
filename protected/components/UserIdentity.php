<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */

	public $username;
	public $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }


	public function authenticate()
	{
		$user = User::model()->findByAttributes(array('username'=>$this->username));
		if(!$user)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else{
			if( $user->hashPassword($this->password,$user->salt) === $user->password )
				$this->errorCode=self::ERROR_NONE;
			else
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		return !$this->errorCode;
	}

	public static function createAuthenticatedIdentity($username) {
		$identity=new self($username,'');
		$identity->username=$username;
		$identity->errorCode=self::ERROR_NONE;
		return $identity;
	}

}
