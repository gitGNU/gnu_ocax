<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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
 * This is the model class for table "vault".
 *
 * The followings are the available columns in table 'vault':
 * @property integer $id
 * @property string $host
 * @property string $name
 * @property integer $type
 * @property string $schedule
 * @property string $created
 * @property integer $state
 *
 * The followings are the available model relations:
 * @property Backup[] $backups
 */
 
//FILTER_SANITIZE_URL
//CUrlValidator
class Vault extends CActiveRecord
{
	public $vaultDir;
	public $key='';
	
	public function init()
	{
		$this->vaultDir = Yii::app()->basePath.'/runtime/vaults/';
		if(!is_dir($this->vaultDir))
			mkdir($this->vaultDir, 0777, true);
	}
	
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
			array('host', 'url'),
			//array('host', 'unique', 'className' => 'Vault'),
			array('type, state', 'numerical', 'integerOnly'=>true),
			array('host', 'length', 'max'=>255),
			array('key', 'length', 'max'=>32),
			array('schedule', 'length', 'max'=>7),
			array('key', 'validateKey', 'on'=>'newKey'),
			array('schedule', 'validateSchedule'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, host, type, schedule, created, state', 'safe', 'on'=>'search'),
		);
	}

	public function validateKey($attribute,$params)
	{
		if (!ctype_alnum($this->key) || strlen($this->key)!=32)
			$this->addError($attribute, __('Not a valid key'));
	}

	public function validateSchedule($attribute,$params)
	{
		if ($this->type == LOCAL && $this->schedule == '0000000')
			$this->addError($attribute, __('Please choose at least one day'));
	}

	public function beforeSave()
	{
		if($this->isNewRecord){
			$this->name = $this->host2VaultName($this->host);
			if(!is_dir($this->vaultDir.$this->name)){
				mkdir($this->vaultDir.$this->name, 0777, true);
				if($this->type == LOCAL){
					$length = 32;
					$this->key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
					file_put_contents($this->vaultDir.$this->name.'/key.txt', $this->key);
				}
			}
		}
		return parent::beforeSave();
    }

	public function host2VaultName($host)
	{
		$name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $host);
		if($this->type == LOCAL)
			return $name.'-local';
		else
			return $name.'-remote';
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
			'host' => __('Host'),
			'type' => __('Type'),
			'schedule' => __('Schedule'),
			'created' => __('Created'),
			'state' => __('State'),
			'key' => __('Key'),
		);
	}

	public static function getHumanStates($state)
	{
		$humanStateValues=array(
				0		=>__('Created'),
				1		=>__('Verified'),
				2		=>__('Agreed'),
		);
		return $humanStateValues[$state];
	}

	public static function getHumanDays($day = Null)
	{
		$humanDayValues=array(
				0		=>__('Monday'),
				1		=>__('Tuesday'),
				2		=>__('Wednesday'),
				3		=>__('Thursday'),
				4		=>__('Friday'),
				5		=>__('Saturday'),
				6		=>__('Sunday'),
		);
		if($day !== Null)
			return $humanDayValues[$day];
		return $humanDayValues;
	}

	public function loadKey()
	{
		if(file_exists($this->vaultDir.$this->name.'/key.txt'))
			$this->key = file_get_contents($this->vaultDir.$this->name.'/key.txt');
	}
	
	public function saveKey()
	{
		file_put_contents($this->vaultDir.$this->name.'/key.txt', $this->key);
	}

	public function getAvailableSchedule()
	{
		$schedule = '0000000';
		foreach($this->findAll() as $vault){
			$day = 0;	// Monday
			while($day < 7){
				if($vault->schedule[$day] == 1)
					$schedule[$day] = 1;
				$day++;
			}
		}
		return $schedule;
	}

	public function getHumanSchedule()
	{
		//return $this->schedule;
		
		$result='';
		$day=0;
		while($day < 7){
			if($this->schedule[$day] === '1')
				$result = $result.' '.$this->getHumanDays($day).',';
			$day++;
		}
		return rtrim($result,',');
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
