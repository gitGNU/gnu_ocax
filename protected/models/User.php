<?php


// email validation, password, salt, from: http://www.ghazalitajuddin.com/general/understand-yii-authentication-intemediat/

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $fullname
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $joined
 * @property integer $activationcode
 * @property integer $is_active
 * @property integer $is_socio
 * @property integer $is_team_member
 * @property integer $is_editor
 * @property integer $is_manager
 * @property integer $is_admin
 */
class User extends CActiveRecord
{
	public $new_password;
	public $password_repeat;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
/* original code via Gii
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, fullname, password, salt, email, joined, activationcode, activationstatus', 'required'),
			array('activationcode, activationstatus, is_socio, is_team_member, is_editor, is_manager', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>32),
			array('fullname', 'length', 'max'=>64),
			array('password, salt, email', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, fullname, password, salt, email, joined, activationcode, activationstatus, is_socio, is_team_member, is_editor, is_manager', 'safe', 'on'=>'search'),
		);
	}
*/
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, fullname, password, salt, email, activationcode', 'required'),
			array('is_socio, is_team_member, is_editor, is_manager, is_admin, is_active', 'numerical', 'integerOnly'=>true),
			array('username, fullname, password, salt, email', 'length', 'max'=>128),
			array('email', 'email','allowEmpty'=>false),
			array('email', 'unique', 'className' => 'User'),
			array('new_password, password_repeat', 'required', 'on'=>'change_password'),
			array('new_password', 'length', 'min' => 6, 
				    'tooShort'=>Yii::t("translation", "{attribute} es muy corta (6 carácteres min)."),
				    'tooLong'=>Yii::t("translation", "{attribute} is too long."),
					'on'=>'change_password'),
			array('password_repeat', 'compare', 'on'=>'change_password', 'compareAttribute'=>'new_password'),
			array('password_repeat', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, salt, email', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'comments' => array(self::HAS_MANY, 'Comment', 'user'),
			'enquirys' => array(self::HAS_MANY, 'Enquiry', 'user'),
			'enquirys1' => array(self::HAS_MANY, 'Enquiry', 'team_member'),
			'enquirys2' => array(self::HAS_MANY, 'Enquiry', 'manager'),
			'enquirySubscribes' => array(self::HAS_MANY, 'EnquirySubscribe', 'user'),
			'emails' => array(self::HAS_MANY, 'Email', 'sender'),
			'replys' => array(self::HAS_MANY, 'Reply', 'team_member'),
			'votes' => array(self::HAS_MANY, 'Vote', 'user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username' => __('Username'),
			'fullname' => __('Full name'),
			'password' => __('Password'),
			'new_password' => __('New password'),
			'password_repeat' => __('Repeat password'),
			'salt' => 'Salt',
			'email' => __('Email'),
			'joined' => __('Joined'),
			'is_active' => 'Active',
			'is_socio' => __('Is partner'),
			'is_team_member' => 'Is Team Member',
			'is_editor' => 'Is Editor',
			'is_manager' => 'Is Manager',
			'is_admin' => 'Is Admin',
		);
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
/* chrisf
	// I commented this out cause it doesn't seem to be used.
	public function validatePassword($password)
	{
		return $this->hashPassword($password,$this->salt)===$this->password;
	}
 */

	/**
	 * Create activation code.
	 * @param string email
	 */
	public function generateActivationCode()
	{
		$code = substr(uniqid(),rand(1,5),15);
		while ($this->findByAttributes(array('activationcode'=>$code)))
			$code = substr(uniqid(),rand(1,5),15);
		return $code;
	}
 
	/**
	 * Generates the password hash.
	 * @param string password
	 * @param string salt
	 * @return string hash
	 */
	public function hashPassword($password,$salt)
	{
		return md5($salt.$password);
	}
 
	/**
	 * Generates a salt that can be used to generate a password hash.
	 * @return string the salt
	 */
	public function generateSalt()
	{
		return uniqid('',true);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('joined',$this->joined,true);
		$criteria->compare('activationcode',$this->activationcode);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('is_socio',$this->is_socio);
		$criteria->compare('is_team_member',$this->is_team_member);
		$criteria->compare('is_editor',$this->is_editor);
		$criteria->compare('is_manager',$this->is_manager);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
