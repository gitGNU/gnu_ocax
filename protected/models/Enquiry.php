<?php

/**
 * This is the model class for table "enquiry".
 *
 * The followings are the available columns in table 'enquiry':
 * @property integer $id
 * @property integer $related_to
 * @property integer $user
 * @property integer $team_member
 * @property integer $manager
 * @property string $created
 * @property string $assigned
 * @property integer $type
 * @property integer $budget
 * @property integer $state
 * @property string $title
 * @property string $body
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Email[] $emails
 * @property Enquiry $relatedTo
 * @property Enquiry[] $reformulateds
 * @property User $user0
 * @property User $teamMember
 * @property User $manager0
 * @property Budget $budget0
 * @property EnquirySubscribe[] $enquirySubscribes
 * @property Reply[] $replys
 */
class Enquiry extends CActiveRecord
{

    public $humanTypeValues=array(
							0=>'Generic',
							1=>'Budgetary',
							2=>'Reclamation',
						);

	public static function getHumanTypes($type)
	{
    	$humanTypeValues=array(
						0=>__('Generic'),
						1=>__('Budgetary'),
						2=>__('Reclamation'),
					);
		return $humanTypeValues[$type];
	}

	public static function getHumanStates($state=Null)
	{
    	$humanStateValues=array(
                        1=>__('Pending validation by the %s'),
						2=>__('Enquiry accepted by the %s'),
                        3=>__('Enquiry rejected by the %s'),
                        4=>__('Awaiting response from the Administration'),
                        5=>__('Replied by the Administration'),
                        6=>__('Reply considered satisfactory'),
                        7=>__('Reply considered insatisfactory'),
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
	 * @return Enquiry the static model class
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
		return 'enquiry';
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
			array('related_to, user, team_member, manager, budget, type, state', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('assigned, body', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, related_to, user, team_member, manager, created, assigned, type, capitulo, state, title, body', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'Comment', 'enquiry'),
			'emails' => array(self::HAS_MANY, 'Email', 'enquiry'),
			'relatedTo' => array(self::BELONGS_TO, 'Enquiry', 'related_to'),
			'reformulateds' => array(self::HAS_MANY, 'Enquiry', 'related_to'),
			'user0' => array(self::BELONGS_TO, 'User', 'user'),
			'teamMember' => array(self::BELONGS_TO, 'User', 'team_member'),
			'manager0' => array(self::BELONGS_TO, 'User', 'manager'),
			'budget0' => array(self::BELONGS_TO, 'Budget', 'budget'),
			'subscriptions' => array(self::HAS_MANY, 'EnquirySubscribe', 'enquiry'),
			'replys' => array(self::HAS_MANY, 'Reply', 'enquiry'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'related_to' => __('Related to'),
			'user' => __('Submitted by'),
			'team_member' => __('Assigned to'),
			'manager' => 'Manager',
			'created' => __('Formulated'),
			'assigned' => __('Assigned'),
			'type' => __('Type'),
			'state' => __('State'),
			'title' => __('Title'),
			'body' => __('Body'),
		);
	}

	public function promptEmail()
	{
		$subscribers = count(EnquirySubscribe::model()->findAll(array('condition'=>'enquiry='.$this->id)));
		Yii::app()->user->setFlash('prompt_email', $subscribers);
	}

	public function getReformulatedEnquires()
	{
		$related_models = $this->_getReformulatedEnquires(array());
		if(count($related_models) == 1)
			return Null;
		sort($related_models);
		return new CArrayDataProvider(array_values($related_models));
	}
	public function _getReformulatedEnquires($result)
	{
		if(!array_key_exists($this->id, $result))
			$result[$this->id]=$this;

		if($this->related_to)
			$result = $this->relatedTo->_getReformulatedEnquires($result);

		foreach($this->reformulateds as $reforumulated){
			if(!array_key_exists($reforumulated->id, $result))
				$result = $reforumulated->_getReformulatedEnquires($result);
		}
		return $result;
	}


	public function countObjects()
	{
		$object_count = array(
							'reforumulated'=>-1,
							'replys'=>0,
							'files'=>0,
							'emails'=>0,
							'comments'=>0,
							'votes'=>0,
							'subscriptions'=>0,
						);
		return $this->_countObjects($object_count);
	}
	public function _countObjects($object_count)
	{
		$object_count['reforumulated'] = $object_count['reforumulated']+1;
		$object_count['replys'] = $object_count['replys']+count($this->replys);
		$object_count['emails'] = $object_count['emails']+count($this->emails);
		$object_count['comments'] = $object_count['comments']+count($this->comments);
		$object_count['subscriptions'] = $object_count['subscriptions']+count($this->subscriptions);
		foreach($this->replys as $reply){
			$object_count['votes'] = $object_count['votes']+count($reply->votes);
			$object_count['comments'] = $object_count['comments']+count($reply->comments);
			$object_count['files'] = $object_count['files']+count(File::model()->findAllByAttributes(array('model'=>'Reply','model_id'=>$reply->id)));
		}
		foreach($this->reformulateds as $reforumulated)
			$object_count = $reforumulated->_countObjects($object_count);
		return $object_count;
	}

	protected function beforeDelete()
	{
		foreach($this->reformulateds as $reformulated)
			$reformulated->delete();
		foreach($this->replys as $reply)
			$reply->delete();
		foreach($this->emails as $email)
			$email->delete();
		foreach($this->comments as $comment)
			$comment->delete();
		foreach($this->subscriptions as $subscription)
			$subscription->delete();

		return parent::beforeDelete();
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
		$criteria->compare('related_to',$this->related_to);
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
