<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $id
 * @property integer $enquiry
 * @property integer $reply
 * @property string $created
 * @property integer $user
 * @property string $body
 *
 * The followings are the available model relations:
 * @property Enquiry $enquiry0
 * @property Reply $reply0
 * @property User $user0
 */
class Comment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
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
		return 'comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created, user, body', 'required'),
			array('enquiry, reply, user', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, enquiry, reply, created, user, body', 'safe', 'on'=>'search'),
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
			'enquiry0' => array(self::BELONGS_TO, 'Enquiry', 'enquiry'),
			'reply0' => array(self::BELONGS_TO, 'Reply', 'reply'),
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
			'enquiry' => __('Enquiry'),
			'reply' => __('Reply'),
			'created' => __('Sent'),
			'user' => __('User'),
			'body' => __('Comment'),
		);
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
		$criteria->compare('enquiry',$this->enquiry);
		$criteria->compare('reply',$this->reply);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('user',$this->user);
		$criteria->compare('body',$this->body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
