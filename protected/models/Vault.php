<?php

/**
 * This is the model class for table "vault".
 *
 * The followings are the available columns in table 'vault':
 * @property integer $id
 * @property string $host
 * @property integer $type
 * @property integer $schedule
 * @property string $created
 * @property integer $state
 *
 * The followings are the available model relations:
 * @property Backup[] $backups
 */
class Vault extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Vault the static model class
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
		return 'vault';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('host, type, schedule, created', 'required'),
			array('type, schedule, state', 'numerical', 'integerOnly'=>true),
			array('host', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, host, type, schedule, created, state', 'safe', 'on'=>'search'),
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
			'backups' => array(self::HAS_MANY, 'Backup', 'vault'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'host' => 'Host',
			'type' => 'Type',
			'schedule' => 'Schedule',
			'created' => 'Created',
			'state' => 'State',
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
		$criteria->compare('host',$this->host,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('schedule',$this->schedule);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}