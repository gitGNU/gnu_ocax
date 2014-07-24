<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

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
 * @property string $modified
 * @property string $assigned
 * @property string $submitted
 * @property string $registry_number
 * @property integer $documentation
 * @property integer $type
 * @property integer $addressed_to
 * @property integer $budget
 * @property integer $state
 * @property string $title
 * @property string $body
 *
 * The followings are the available model relations:
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
	public $username=Null;
	public $searchDate_min=Null;
	public $searchDate_max=Null;
	public $searchText=Null;

	public function getHumanTypes($type=Null)
	{
		$humanTypeValues=array(
				0=>__('Generic'),
				1=>__('Budgetary'),
		);

		if($type === Null){
			$types=array();
			foreach($humanTypeValues as $key=>$value)
				$types[$key]=__($value);
			return $types;
		}
		return __($humanTypeValues[$type]);
	}

	public function getHumanAddressedTo($address=Null)
	{
		$humanAddressValues=array(
				0=>Config::model()->findByPk('administrationName')->value,
				1=>__('The observatory'),
		);

		if($address === Null){
			$addresses=array();
			foreach($humanAddressValues as $key=>$value)
				$addresses[$key]=__($value);
			return $addresses;
		}
		return __($humanAddressValues[$address]);
	}
	
	public static function getHumanStates($state=Null, $addressed_to=ADMINISTRATION)
	{
		$humanStateValues=array(
				ENQUIRY_PENDING_VALIDATION		=>__('Pending validation by the %s'),
				ENQUIRY_ASSIGNED				=>__('Enquiry assigned to team member'),
				ENQUIRY_REJECTED				=>__('Enquiry rejected by the %s'),
				ENQUIRY_ACCEPTED				=>__('Enquiry accepted by the %s'),
				ENQUIRY_AWAITING_REPLY			=>'',
				ENQUIRY_REPLY_PENDING_ASSESSMENT=>__('Reply pending assessment'),
				ENQUIRY_REPLY_SATISFACTORY		=>__('Reply considered satisfactory'),
				ENQUIRY_REPLY_INSATISFACTORY	=>__('Reply considered insatisfactory'),
		);
		if($addressed_to)
			$humanStateValues[ENQUIRY_AWAITING_REPLY]=__('Awaiting reply from the observatory');
		else
			$humanStateValues[ENQUIRY_AWAITING_REPLY]=__('Awaiting reply from the administration');

		if($state!==Null){
			if(!(Yii::app()->user->isTeamMember() || Yii::app()->user->isManager()) && $state==ENQUIRY_ASSIGNED)
				$state=ENQUIRY_PENDING_VALIDATION;
			$value=$humanStateValues[$state];
			if( strpos($value, '%s') !== false){
				$value = str_replace("%s", Config::model()->findByPk('siglas')->value, $value);
			}
			return $value;
		}
		$siglas=Config::model()->findByPk('siglas')->value;
		$states = array();
		foreach($humanStateValues as $key=>$value){
			if( strpos($value, '%s') !== false){
				if($key == ENQUIRY_AWAITING_REPLY){
					if($addressed_to == OBSERVATORY)
						$value = str_replace("%s", __('the observatory'), $value);
					else
						$value = str_replace("%s", __('the administration'), $value);
				}else
					$value = str_replace('%s', $siglas, $value);
			}
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
			array('user, created, title, body, addressed_to', 'required'),
			array('submitted, registry_number', 'required', 'on'=>'submitted_to_council'),
			array('related_to, user, team_member, manager, budget, type, state, documentation', 'numerical', 'integerOnly'=>true),
			array('title', 'validTitle'),
			array('title', 'length', 'max'=>255),
			array('registry_number', 'length', 'max'=>32),
			array('addressed_to, assigned, submitted, body', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array( 'related_to, user, username, team_member,
					manager, created,
					assigned, type, state,
					title, body,
					searchText, searchDate_min, searchDate_max',
					'safe', 'on'=>'search'),
		);
	}

	public function validTitle($attribute,$params)
	{
		if (strpos($this->title,'?') !== false) {
			$this->addError($attribute, __('The title cannot include the question.'));
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'emails' => array(self::HAS_MANY, 'Email', 'enquiry'),
			'relatedTo' => array(self::BELONGS_TO, 'Enquiry', 'related_to'),
			'reformulateds' => array(self::HAS_MANY, 'Enquiry', 'related_to'),
			'user0' => array(self::BELONGS_TO, 'User', 'user'),
			'teamMember' => array(self::BELONGS_TO, 'User', 'team_member'),
			'manager0' => array(self::BELONGS_TO, 'User', 'manager'),
			'documentation0' => array(self::BELONGS_TO, 'File', 'documentation'),
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
			'user' => __('Formulated by'),
			'team_member' => __('Assigned to'),
			'manager' => __('Manager'),
			'created' => __('Formulated'),
			'assigned' => __('Assigned'),
			'submitted'=>__('Submitted'),
			'registry_number'=>__('Registry number'),
			'documentation'=>__('Documentation'),
			'type' => __('Type'),
			'addressed_to' => __('Addressed to'),
			'state' => __('State'),
			'title' => __('Title'),
			'body' => __('Body'),
		);
	}
	
	public function getEmailRecipients()
	{
		if($this->state == ENQUIRY_ASSIGNED){ 	// internal email to team_member
			$criteria = array('condition'=>'id = '.$this->teamMember->id);
		}else{
			$criteria = array(
				'with'=>array('enquirySubscribes'),
				'condition'=>' enquirySubscribes.enquiry = '.$this->id.' AND is_disabled = 0',
				'together'=>true,
			);
		}
		return User::model()->findAll($criteria);
	}

	public function promptEmail()
	{
		if($this->state == ENQUIRY_ASSIGNED)
			$str = __('Send an email to the').' '.__('team member').'?';
		else
			$str = __('Send an email to the').' '.count($this->getEmailRecipients()).' '.__('people subscribed to the Enquiry').'?';
		Yii::app()->user->setFlash('prompt_email', $str );
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
		$object_count['comments'] = $object_count['comments']+count(Comment::model()->findAllByAttributes(array('model'=>'Enquiry','model_id'=>$this->id)));
		$object_count['subscriptions'] = $object_count['subscriptions']+count($this->subscriptions);
		$object_count['files'] = $object_count['files']+count(File::model()->findAllByAttributes(array('model'=>'Enquiry','model_id'=>$this->id)));
		foreach($this->replys as $reply){
			$object_count['votes'] = $object_count['votes']+count($reply->votes);
			$object_count['comments'] = $object_count['comments']+count(Comment::model()->findAllByAttributes(array('model'=>'Reply','model_id'=>$reply->id)));
			$object_count['files'] = $object_count['files']+count(File::model()->findAllByAttributes(array('model'=>'Reply','model_id'=>$reply->id)));
		}
		foreach($this->reformulateds as $reforumulated)
			$object_count = $reforumulated->_countObjects($object_count);
		return $object_count;
	}

	protected function beforeDelete()
	{
		if($text=EnquiryText::model()->findByPk($this->id))	//remove if statement when dev.ocax is ready
			$text->delete();
		foreach($this->reformulateds as $reformulated)
			$reformulated->delete();
		foreach($this->replys as $reply)
			$reply->delete();
		foreach($this->emails as $email)
			$email->delete();
		foreach($this->subscriptions as $subscription)
			$subscription->delete();
		return parent::beforeDelete();
	}

	protected function afterDelete()
	{
		parent::afterDelete();
		if($file = File::model()->findByAttributes(array('model'=>'Enquiry','model_id'=>$this->id)))
			$file->delete();
		$comments = Comment::model()->findAllByAttributes(array('model'=>'Enquiry','model_id'=>$this->id));
		foreach($comments as $comment)
			$comment->delete();
	}

	public function getEnquiriesForRSS()
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition('state != '.ENQUIRY_PENDING_VALIDATION.
								' AND state != '.ENQUIRY_ASSIGNED.
								' AND state != '.ENQUIRY_REJECTED);
		$criteria->order = 'created DESC';
		$criteria->limit = '20';
		
		return $this->findAll($criteria);
	}
	

	public function publicSearch()
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition('state != '.ENQUIRY_PENDING_VALIDATION.
								' AND state != '.ENQUIRY_ASSIGNED.
								' AND state != '.ENQUIRY_REJECTED);

		$searchDate_min=Null;
		$searchDate_max=Null;
		if($this->searchDate_min){
			$searchDate_min = DateTime::createFromFormat('d/m/Y', $this->searchDate_min);
			$searchDate_min->modify('-1 day');
			$searchDate_min = $searchDate_min->format('Y-m-d H:i:s');
			if(!$this->searchDate_max){
				$searchDate_max = new DateTime('c');
				$searchDate_max = $searchDate_max->format('Y-m-d H:i:s');
			}
		}
		if($this->searchDate_max){
			$searchDate_max = DateTime::createFromFormat('d/m/Y', $this->searchDate_max);
			$searchDate_max = $searchDate_max->format('Y-m-d H:i:s');			
			if(!$this->searchDate_min){
				$searchDate_min = new DateTime("2012-12-12");	// this is the past
				$searchDate_min = $searchDate_min->format('Y-m-d H:i:s');
			}
		}
		if($searchDate_min && $searchDate_max)
			$criteria->condition = 'created BETWEEN "'.$searchDate_min.'" AND "'.$searchDate_max.'"';
		
		if($this->searchText){
			$text = mysql_real_escape_string($this->searchText);
			$criteria->addCondition("title LIKE '%$text%' OR body LIKE '%$text%'");
		}
		$criteria->compare('type',$this->type);
		$criteria->compare('state',$this->state);
		$criteria->compare('addressed_to',$this->addressed_to, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'modified DESC'),
		));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function teamMemberSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with=array('user0');
		$criteria->compare('user0.username', $this->username, true);
		
		$criteria->compare('team_member',Yii::app()->user->getUserID());
		$criteria->compare('type',$this->type);
		$criteria->compare('addressed_to',$this->addressed_to);
		$criteria->compare('state',$this->state);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('body',$this->body,true);
		//$criteria->compare('related_to',$this->related_to);
		//$criteria->compare('created',$this->created,true);
		//$criteria->compare('assigned',$this->assigned,true);
		//$criteria->compare('budget',$this->budget);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'created DESC'),
		));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function adminSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		//http://www.yiiframework.com/forum/index.php/topic/8148-cgridview-filter-with-relations/
		$criteria->with=array('user0');

		$criteria->compare('user0.username', $this->username, true);
		$criteria->compare('team_member',$this->team_member);
		$criteria->compare('manager',$this->manager);
		//$criteria->compare('assigned',$this->assigned);
		$criteria->compare('type',$this->type);
		$criteria->compare('addressed_to',$this->addressed_to);
		$criteria->compare('budget',$this->budget);
		$criteria->compare('state',$this->state);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('body',$this->body,true);
		//$criteria->compare('related_to',$this->related_to);
		//$criteria->compare('created',$this->created,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'created DESC'),
		));
	}
}
