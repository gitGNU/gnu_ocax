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
 * This is the model class for table "backup".
 *
 * The followings are the available columns in table 'backup':
 * @property integer $id
 * @property integer $vault
 * @property string $filename
 * @property string $initiated
 * @property string $completed
 * @property string $checksum
 * @property integer $state
 *
 * The followings are the available model relations:
 * @property Vault $vault0
 */
class Backup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Backup the static model class
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
		return 'backup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vault, initiated', 'required'),
			array('vault, state', 'numerical', 'integerOnly'=>true),
			array('filename, checksum', 'length', 'max'=>255),
			array('completed', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, vault, filename, initiated, completed, checksum, state', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'vault' => 'Vault',
			'filename' => 'Filename',
			'initiated' => 'Initiated',
			'completed' => 'Completed',
			'checksum' => 'Checksum',
			'state' => 'State',
		);
	}


	public function getDataproviderByVault($vault_id)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition('vault ='.$vault_id);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			//'sort'=>array('defaultOrder'=>'created DESC'),
		));
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
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('initiated',$this->initiated,true);
		$criteria->compare('completed',$this->completed,true);
		$criteria->compare('checksum',$this->checksum,true);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
