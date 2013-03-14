<?php

/**
 * This is the model class for table "respuesta".
 *
 * The followings are the available columns in table 'respuesta':
 * @property integer $id
 * @property integer $consulta
 * @property string $created
 * @property integer $team_member
 * @property string $body
 *
 * The followings are the available model relations:
 * @property Consulta $consulta0
 * @property User $teamMember
 */
class Respuesta extends CActiveRecord
{

	public $state;	// used to get the Consulta state from respuesta/create form

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Respuesta the static model class
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
		return 'respuesta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('consulta, created, team_member, body', 'required'),
			array('consulta, team_member', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, consulta, created, team_member, body', 'safe', 'on'=>'search'),
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
			'consulta0' => array(self::BELONGS_TO, 'Consulta', 'consulta'),
			'teamMember' => array(self::BELONGS_TO, 'User', 'team_member'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'consulta' => __('Consultation'),
			'created' => __('Created'),
			'team_member' => 'Team Member',
			'body' => 'Body',
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
		$criteria->compare('created',$this->created,true);
		$criteria->compare('team_member',$this->team_member);
		$criteria->compare('body',$this->body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
