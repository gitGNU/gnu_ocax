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
 * This is the model class for table "config".
 *
 * The followings are the available columns in table 'config':
 * @property string $parameter
 * @property string $value
 * @property string $description
 */
class Config extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Config the static model class
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
		return 'config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('value', 'required', 'except'=>'canBeEmpty'),
			array('parameter, description', 'required'),
			array('value', 'length', 'max'=>255),
			array('value','validateLanguage', 'on'=>'language'),
			array('value','validateCurrenyCollocation', 'on'=>'currenyCollocation'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('parameter, value, description', 'safe', 'on'=>'search'),
		);
	}


	public function validateLanguage($attribute,$params)
	{
		$available_langs = Yii::app()->coreMessages->basePath;
		$languages = explode(',', $this->$attribute);
		foreach($languages as $language){
			if(!is_dir($available_langs.'/'.$language)){
				$this->addError($attribute, $language.' '.__('is not a valid language.'));
			}				
		}		
	}

	public function validateCurrenyCollocation($attribute,$params)
	{
		$this->$attribute = trim($this->$attribute);
		if(stristr($this->$attribute, 'n') === FALSE) {
			$this->addError($attribute, __("Character 'n' is missing."));
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'parameter' => __('Parameter'),
			'value' => __('Value'),
			'description' => __('Description'),
		);
	}

	public function getSiteTitle()
	{
		$title=str_replace('%s', '<span id="nombre_ocax">'.$this->findByPk('councilName')->value.'</span>', $this->findByPk('observatoryName')->value);
		return str_replace('#', '<br />', $title);
	}

	public function getObservatoryName()
	{
		$title=str_replace('%s', $this->findByPk('councilName')->value, $this->findByPk('observatoryName')->value);
		return str_replace('#', ' ', $title);
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

		$criteria->compare('parameter',$this->parameter,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
