<?php

/**
 * This is the model class for table "consulta_subscribe".
 *
 * The followings are the available columns in table 'consulta_subscribe':
 * @property integer $id
 * @property integer $consulta
 * @property integer $user
 *
 * The followings are the available model relations:
 * @property User $user0
 * @property Consulta $consulta0
 */
class ConsultaSubscribe extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ConsultaSubscribe the static model class
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
		return 'consulta_subscribe';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('consulta, user', 'required'),
			array('consulta, user', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, consulta, user', 'safe', 'on'=>'search'),
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
			'consulta0' => array(self::BELONGS_TO, 'Consulta', 'consulta'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'consulta' => 'Consulta',
			'user' => 'User',
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
		$criteria->compare('consulta',$this->consulta);
		$criteria->compare('user',$this->user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
