<?php

/**
 * This is the model class for table "budget_description".
 *
 * The followings are the available columns in table 'budget_description':
 * @property integer $id
 * @property string $code
 * @property integer $type
 * @property string $language
 * @property string $concept
 * @property string $description
 */
class BudgetDescription extends CActiveRecord
{
	public $combination;
	
	public function getHumanTypes($type=Null)
	{
		$humanTypeValues=array(
				0=>__('Income'),
				1=>__('Spenditure'),
		);

		if($type == Null){
			$types=array();
			foreach($humanTypeValues as $key=>$value)
				$types[$key]=__($value);
			return $types;
		}
		return __($humanTypeValues[$type]);
	}

	public function getHumanLanguages($lang)
	{
		$languages=getLanguagesArray();
		if($lang)
			return $languages[$lang];
		return $languages;
	}
	
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
			array('type, code, language, concept', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('language', 'length', 'max'=>2),
			array('code', 'length', 'max'=>20),
			array('combination', 'validCombination', 'on'=>'create'),
			array('concept', 'length', 'max'=>255),
			array('combination, description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('code, type, language, concept, description', 'safe', 'on'=>'search'),
		);
	}

	public function validCombination($attribute,$params)
	{
			if($this->findByAttributes(array('type'=>$this->type,'language'=>$this->language,'code'=>$this->code))){
				$this->addError($attribute, __('Type/Language/Code combination already exists.'));
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
			'code' => __('Code'),
			'type' => __('Type'),
			'language' => __('Language'),
			'concept' => __('Concept'),
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

		$criteria->compare('code',$this->code,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
