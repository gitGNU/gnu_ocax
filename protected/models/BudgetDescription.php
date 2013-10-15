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
 * This is the model class for table "budget_description".
 *
 * The followings are the available columns in table 'budget_description':
 * @property integer $id
 * @property string $new_id
 * @property string $csv_id
 * @property string $language
 * @property string $code
 * @property string $label
 * @property string $concept
 * @property string $description
 * @property string $text
 * @property integer $common
 * @property string $modified
 */
class BudgetDescription extends CActiveRecord
{
	
	public $combination;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BudgetDescription the static model class
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
		return 'budget_description';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('csv_id, language, concept', 'required'),
			array('common', 'numerical', 'integerOnly'=>true),
			array('csv_id, code', 'length', 'max'=>32),
			array('label', 'length', 'max'=>32),
			array('language', 'length', 'max'=>2),
			array('combination', 'validCombination', 'on'=>'create'),
			array('id', 'unique', 'className' => 'BudgetDescription', 'on'=>'create'),
			array('concept', 'length', 'max'=>255),
			array('description, text, new_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('csv_id, language, code, concept, description, text', 'safe', 'on'=>'search'),
		);
	}

	protected function beforeSave()
	{
		if(!$this->id)
			$this->id = $this->language.$this->csv_id;
		return parent::beforeSave();
	}


	public function validCombination($attribute,$params)
	{
			if($this->findByAttributes(array('csv_id'=>$this->csv_id,'language'=>$this->language))){
				$this->addError($attribute, __('Internal_code/Language combination already exists.'));
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
			'id' => 'ID',
			'csv_id' => 'Internal_code',
			'language' => __('Language'),
			'code' => __('Code'),
			'label' => __('Label'),
			'concept' => __('Concept'),
			'description' => __('Description'),
			'text' => 'Text',
			'common' => __('Common'),
			'modified' => __('Modified'),
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

		//$criteria->compare('id',$this->id);
		$criteria->compare('csv_id',$this->csv_id,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('code',$this->code);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('description',$this->description,true);
		//$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'csv_id ASC'),
		));
	}
}
