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
 * This is the model class for table "vault_schedule".
 *
 * The followings are the available columns in table 'vault_schedule':
 * @property integer $id
 * @property integer $vault
 * @property integer $day
 *
 * The followings are the available model relations:
 * @property Vault $vault0
 */

/**

R = I will save L's copies on my server
L = R will save my copies on his server

0. R runVaultSchedule();
1.	R -> L Have you got your dump ready?
	R calls L's vault/remoteWaitingToStartCopyingBackup
	
2.	L -> R Yes. start copying.
	L calls R's valut/startCopyingBackup

 */
 
class VaultSchedule extends CActiveRecord
{
	public $backupHour = 12;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return VaultSchedule the static model class
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
		return 'vault_schedule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vault, day', 'required'),
			array('vault, day', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, vault, day', 'safe', 'on'=>'search'),
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
			'vault0' => array(self::BELONGS_TO, 'Vault', 'vault'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'	=> 'ID',
			'vault'	=> 'Vault',
			'day'	=> 'Day',
		);
	}

	/**
	 * Does this LOCAL vault initiate the backup process?
	 */
	public function runVaultSchedule()
	{
		if(date('G') != $this->backupHour)
			return;
		if($schedule = $this->findByAttributes(array('day'=>date('N')-1))){
			if($schedule->vault0->state == BUSY)
				return;
			if($schedule->vault0->state == READY){
				//if(have we made a backup today?)
				//	return;

				$vaultName = rtrim($this->vault0->name, '-remote');
				@file_get_contents($this->vault0->host.'/vault/remoteWaitingToStartCopyingBackup'.
														'?key='.$this->vault0->key.
														'&vault='.$vaultName,
														false,
														$this->vault0->getStreamContext()
									);
				return;
			}
		}
		return;
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
		$criteria->compare('vault',$this->vault);
		$criteria->compare('day',$this->day);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
