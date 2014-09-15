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
			array('value', 'required', 'on'=>'cannotBeEmpty'),
			array('parameter, description', 'required'),
			array('value', 'length', 'max'=>255),
			array('value','validateLanguage', 'on'=>'language'),
			array('value', 'url', 'on'=>'URL', 'allowEmpty'=>true),
			array('value','validateCurrenyCollocation', 'on'=>'currenyCollocation'),
			array('value', 'email', 'on'=>'email', 'allowEmpty'=>false),
			array('value', 'numerical', 'on'=>'positiveNumber', 'allowEmpty'=>false, 'min'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('parameter, value, description', 'safe', 'on'=>'search'),
		);
	}

	public function validateLanguage($attribute,$params)
	{
		if($this->$attribute === ""){
				$this->addError($attribute, __('Please define a language'));
				return;
		}
		$available_langs = Yiibase::getPathOfAlias('application.messages');
		$languages = explode(',', $this->$attribute);
		foreach($languages as $language){
			if(!is_dir($available_langs.'/'.$language)){
				$this->addError($attribute, $language.' '.__('is not a valid language.'));
			}
		}
	}

	public function validateCurrenyCollocation($attribute,$params)
	{
		if($this->$attribute === ""){
				$this->addError($attribute, __('Missing value'));
				return;
		}
		$this->$attribute = trim($this->$attribute);
		if(stristr($this->$attribute, 'n') === FALSE) {
			$this->addError($attribute, __("Character 'n' is missing."));
		}
	}

	protected function afterSave()
	{
		if($this->parameter == 'languages'){
			$record = $this->findByPk('siteConfigStatusLanguage');
			$record->value = 1;
			$record->save();
			return $this->_updateSiteConfigurationStatus();
		}
		elseif($this->parameter == 'siglas'){
			$record = $this->findByPk('siteConfigStatusInitials');
			$record->value = 1;
			$record->save();
			return $this->_updateSiteConfigurationStatus();
		}
		elseif($this->parameter == 'observatoryName1' || $this->parameter == 'observatoryName2'){
			$record = $this->findByPk('siteConfigStatusObservatoryName');
			$record->value = 1;
			$record->save();
			return $this->_updateSiteConfigurationStatus();
		}
		elseif($this->parameter == 'administrationName'){
			$record = $this->findByPk('siteConfigStatusAministrationName');
			$record->value = 1;
			$record->save();
			return $this->_updateSiteConfigurationStatus();
		}
	}

	private function _updateSiteConfigurationStatus()
	{
		$siteConfigStatus = $this->findByPk('siteConfigStatus');

		$sql = "SELECT COUNT(*) FROM budget_desc_common";
		if(intval(Yii::app()->db->createCommand($sql)->queryScalar()) != 0){
			$param = $this->findByPk('siteConfigStatusBudgetDescriptionsImport');
			if($param->value != 1){
				$param->value =1;
				$param->save();
			}
		}
		$params = array('siteConfigStatusLanguage',
						'siteConfigStatusEmail',
						'siteConfigStatusInitials',
						'siteConfigStatusObservatoryName',
						'siteConfigStatusAdministrationName',
						'siteConfigStatusBudgetDescriptionsImport',
						'siteConfigStatusZipFileCreated',
					);
		foreach($params as $p){
			if($this->findByPk($p)->value == 0){
				$siteConfigStatus->value=0;
				$siteConfigStatus->save();
				return 0;
			}
		}
		$siteConfigStatus->value=1;
		$siteConfigStatus->save();
		return 1;
	}

	public function updateSiteConfigurationStatus($param=Null, $value=Null)
	{
		if($param && $value){
			$record = $this->findByPk($param);
			$record->value = $value;
			$record->save();
		}
		return $this->_updateSiteConfigurationStatus();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
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
		$title=str_replace('%s', '<span id="nombre_ocax">'.$this->findByPk('observatoryName2')->value.'</span>', $this->findByPk('observatoryName1')->value);
		return str_replace('#', '<br />', $title);
	}

	public function getObservatoryName()
	{
		$title=str_replace('%s', $this->findByPk('observatoryName2')->value, $this->findByPk('observatoryName1')->value);
		return str_replace('#', ' ', $title);
	}
}
