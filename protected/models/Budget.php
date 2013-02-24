<?php

/**
 * This is the model class for table "budget".
 *
 * The followings are the available columns in table 'budget':
 * @property integer $id
 * @property integer $parent
 * @property integer $level
 * @property integer $year
 * @property string $csv_id
 * @property string $code
 * @property string $label
 * @property string $concept
 * @property integer $provision
 * @property integer $spent
 * @property integer $weight
 *
 * The followings are the available model relations:
 * @property Budget $parent0
 * @property Budget[] $budgets
 * @property Consulta[] $consultas
 */
class Budget extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Budget the static model class
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
		return 'budget';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('year, concept, provision, spent', 'required'),
			array('parent, year, weight', 'numerical', 'integerOnly'=>true),
			array('provision, spent', 'type', 'type'=>'float'),
			array('code, csv_id', 'length', 'max'=>20),
			array('label, concept', 'length', 'max'=>255),
			array('year', 'unique', 'className'=>'Budget', 'on'=>'newYear'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent, year, code, label, concept, provision, spent, weight', 'safe', 'on'=>'search'),
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
			'parent0' => array(self::BELONGS_TO, 'Budget', 'parent'),
			'budgets' => array(self::HAS_MANY, 'Budget', 'parent'),
			'consultas' => array(self::HAS_MANY, 'Consulta', 'budget'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent' => 'Parent',
			'csv_id' => 'internal code',
			'year' => 'Year',
			'code' => 'Código',
			'label' => 'Label',
			'concept' => 'Concept',
			'provision' => 'Importe provisto',
			'spent' => 'Importe real',
			'spent' => 'Spent',
			'weight' => 'Weight',
		);
	}

	public function publicSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->addCondition('parent is not null');	// dont show year budgets

		$criteria->compare('id',$this->id);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('year',$this->year);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('provision',$this->provision);
		$criteria->compare('spent',$this->spent);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
		//$criteria->addCondition('parent is not null');	// dont show year budgets

		$criteria->compare('id',$this->id);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('csv_id',$this->csv_id);
		$criteria->compare('year',$this->year);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('provision',$this->provision);
		$criteria->compare('spent',$this->spent);
		$criteria->compare('weight',$this->weight);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
