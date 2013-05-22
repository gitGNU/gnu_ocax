<?php

/**
 * This is the model class for table "budget_description".
 *
 * The followings are the available columns in table 'budget_description':
 * @property integer $id
 * @property string $csv_id
 * @property string $language
 * @property string $code
 * @property string $concept
 * @property string $description
 * @property string $text
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
			array('csv_id, code', 'length', 'max'=>20),
			array('language', 'length', 'max'=>2),
			array('combination', 'validCombination', 'on'=>'create'),
			array('concept', 'length', 'max'=>255),
			array('description, text', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, csv_id, language, code, concept, description, text', 'safe', 'on'=>'search'),
		);
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
			'concept' => __('Concept'),
			'description' => __('Description'),
			'text' => 'Text',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('csv_id',$this->csv_id,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('code',$this->code);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
