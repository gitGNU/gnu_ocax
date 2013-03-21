<?php

/**
 * This is the model class for table "email".
 *
 * The followings are the available columns in table 'email':
 * @property integer $id
 * @property integer $type
 * @property string $created
 * @property integer $sent
 * @property string $title
 * @property integer $sender
 * @property string $sent_as
 * @property string $recipients
 * @property integer $enquiry
 * @property string $body
 *
 * The followings are the available model relations:
 * @property User $sender0
 * @property Enquiry $enquiry0
 */
class Email extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Email the static model class
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
		return 'email';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created, title, sent_as, recipients, enquiry, body', 'required'),
			array('sent, enquiry', 'numerical', 'integerOnly'=>true),
			array('sender, type', 'safe'),
			array('title', 'length', 'max'=>255),
			array('sent_as', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, created, sent, title, sender, sent_as, recipients, enquiry, body', 'safe', 'on'=>'search'),
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
			'sender0' => array(self::BELONGS_TO, 'User', 'sender'),
			'enquiry0' => array(self::BELONGS_TO, 'Enquiry', 'enquiry'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => __('Type'),
			'created' => __('Created'),
			'sent' => __('Sent'),
			'title' => __('Title'),
			'sender' => __('Sender'),
			'sent_as' => __('Sent as'),
			'recipients' => __('Recipients'),
			'enquiry' => __('Enquirytion'),
			'body' => __('Body'),
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

		$criteria->compare('id',$this->