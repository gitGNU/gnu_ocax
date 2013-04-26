<?php

/**
 * This is the model class for table "reset_password".
 *
 * The followings are the available columns in table 'reset_password':
 * @property integer $id
 * @property integer $user
 * @property string $code
 * @property string $created
 *
 * The followings are the available model relations:
 * @property User $user0
 */
class ResetPassword extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ResetPassword the static model class
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
		return 'reset_password';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user, code, created', 'required'),
			array('user', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user, code, created', 'safe', 'on'=>'search'),
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
			'user0' => array(self::BELONGS_TO, 'User', 'user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user' => 'User',
			'code' => 'Code',
			'created' => 'Created',
		);
	}


	private function _createCode()
	{
		$code='';
		$length=15;
		$charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$count = strlen($charset);
		while ($length--)
			$code .= $charset[mt_rand(0, $count-1)];
		return $code;
	}
 	public function createCode()
	{
		$code = $this->_createCode();
		while($this->findAllByAttributes(array('code'=>$code)))
			$code = $this->_createCode();
		$this->code = $code;
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
		$criteria->compare('user',$this->user);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
