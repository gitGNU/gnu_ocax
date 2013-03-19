<?php

/**
 * This is the model class for table "consulta".
 *
 * The followings are the available columns in table 'consulta':
 * @property integer $id
 * @property integer $user
 * @property integer $team_member
 * @property integer $manager
 * @property string $created
 * @property string $assigned
 * @property integer $type
 * @property integer $capitulo
 * @property integer $state
 * @property string $title
 * @property string $body
 *
 * The followings are the available model relations:
 * @property User $user0
 * @property User $teamMember
 * @property User $manager0
 */
class Consulta extends CActiveRecord
{

    public $humanTypeValues=array(
							0=>'Generic',
							1=>'Budgetary', //Pressupostària
						);

	public static function getHumanStates($state=Null)
	{
    	$humanStateValues=array(
                        0=>__('Waiting for the %s to reply'),
						1=>__('The %s acknowledges the enquiry'),
                        2=>__('Rejected by the %s'),
                        3=>__('Waiting for the Administration to reply'),
                        4=>__('Duly replied by the Administration'),
                        5=>__('Parcially answered by the Administration'),
                        6=>__('Rejected by the Administration'),
					);
		if($state!==Null){
			$str=$humanStateValues[$state];
			if( strpos($str, '%s') !== false){
				$str = str_replace("%s", Config::model()->findByPk('siglas')->value, $str);
			}
			return $str;
		}
		$siglas=Config::model()->findByPk('siglas')->value;
		$states = array();
		foreach($humanStateValues as $key=>$value){
			if( strpos($value, '%s') !== false)
				$value = str_replace('%s', $siglas, $value);
			$states[$key]=$value;
		}
		return $states;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Consulta the static model class
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
		return 'consulta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user, created, title, body', 'required'),
			array('user, team_member, manager, budget, type, state', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('assigned', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user, team_member, manager, created, assigned, type, capitulo, state, title, body', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'Comment', 'consulta'),
			'budget0' => array(self::BELONGS_TO, 'Budget', 'budget'),
			'user0' => array(self::BELONGS_TO, 'User', 'user'),
			'teamMember' => array(self::BELONGS_TO, 'User', 'team_member'),
			'manager0' => array(self::BELONGS_TO, 'User', 'manager'),
			'subscriptions' => array(self::HAS_MANY, 'ConsultaSubscribe', 'consulta'),
			'emails' => array(self::HAS_MANY, 'Email', 'consulta'),
			'respuestas' => array(self::HAS_MANY, 'Respuesta', 'consulta'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user' => __('Submitted by'),
			'team_member' => __('Assigned to'),
			'manager' => 'Manager',
			'created' => __('Formulated'),
			'assigned' => __('Assigned'),
			'type' => __('Type'),
			'capitulo' => 'Capitulo',
			'state' => __('State'),
			'title' => __('Title'),
			'body' => __('Body'),
		);
	}

	public function promptEmail()
	{
		$subscribers = count(ConsultaSubscribe::model()->findAll(array('condition'=>'consulta='.$this->id)));
		Yii::app()->user->setFlash('prompt_email', $subscribers);
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

		//$criteria->compare('id',$this->id);
		$criteria->compare('user',$this->user);
		$criteria->compare('team_member',$this->team_member);
		$criteria->compare('manager',$this->manager);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('assigned',$this->assigned,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('budget',$this->budget);
		$criteria->compare('state',$this->state);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('body',$this->body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'created DESC'),
		));
	}
}
