<?php

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
			array('parameter, value, description', 'required'),
			array('parameter', 'length', 'max'=>64),
			array('value, description', 'length', 'max'=>255),
			array('value','validateLanguage', 'on'=>'language'),
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
