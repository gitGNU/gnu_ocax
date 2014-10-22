<?php

/**
 * This is the model class for table "budget_desc_local".
 *
 * The followings are the available columns in table 'budget_desc_local':
 * @property integer $id
 * @property string $csv_id
 * @property string $language
 * @property string $code
 * @property string $label
 * @property string $concept
 * @property string $description
 * @property string $text
 * @property string $modified
 */
class BudgetDescLocal extends CActiveRecord
{
	public $combination;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BudgetDescLocal the static model class
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
		return 'budget_desc_local';
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
			array('csv_id', 'length', 'max'=>100),
			array('code', 'length', 'max'=>32),
			array('label', 'length', 'max'=>32),
			array('language', 'length', 'max'=>2),
			array('combination', 'validCombination', 'on'=>'create'),
			array('concept', 'length', 'max'=>255),
			array('description, text', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('csv_id, language, code, concept, text', 'safe', 'on'=>'search'),
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
			'label' => __('Label'),
			'concept' => __('Concept'),
			'description' => __('Description'),
			'text' => 'Text',
			'modified' => __('Modified'),
		);
	}

	public function getDescriptionFieldsForDisplay($language = null)
	{
		if(!$language)
			$language = Yii::app()->language;
		
		$fields = array('label'=>null, 'concept'=>null, 'description'=>null);
		
		if($description = BudgetDescLocal::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>$language))){
			if($description->label)
				$fields['label'] = $description->label;
			if($description->concept)
				$fields['concept'] = $description->concept;
			if($description->description)
				$fields['description'] = $description->description;
			if($fields['label'] && $fields['concept'] && $fields['description'])
				return $fields;
		}
		if($description = BudgetDescCommon::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>$language))){
			if(!$fields['label'] && $description->label)
				$fields['label'] = $description->label;
			if(!$fields['concept'] && $description->concept)
				$fields['concept'] = $description->concept;
			if(!$fields['description'] && $description->description)
				$fields['description'] = $description->description;
			if($fields['label'] && $fields['concept'] && $fields['description'])
				return $fields;
		}		
		if($description = BudgetDescState::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>$language))){
			if(!$fields['label'] && $description->label)
				$fields['label'] = $description->label;
			if(!$fields['concept'] && $description->concept)
				$fields['concept'] = $description->concept;
			if(!$fields['description'] && $description->description)
				$fields['description'] = $description->description;
			return $fields;
		}			
	}


	/* years that have budgets that use a local description */
	public function whereUsed()
	{	

		$budgets =  Budget::model()->findAll("csv_id = '".$this->csv_id."' ORDER BY year ASC");

		$result = '';
		foreach($budgets as $budget)
				$result = $budget->year.', '.$result;
		return rtrim($result, ' ,');
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

		$criteria->compare('csv_id',$this->csv_id,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('text',$this->text,true);
		//$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'csv_id ASC'),
		));
	}
}
