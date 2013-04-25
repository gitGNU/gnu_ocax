<?php

/**
 * This is the model class for table "budget".
 *
 * The followings are the available columns in table 'budget':
 * @property integer $id
 * @property integer $parent
 * @property integer $year
 * @property string $csv_id
 * @property string $csv_parent_id
 * @property string $code
 * @property string $label
 * @property string $concept
 * @property string $initial_provision
 * @property string $actual_provision
 * @property string $trimester_1
 * @property string $trimester_2
 * @property string $trimester_3
 * @property string $trimester_4
 * @property integer $featured
 * @property integer $weight
 *
 * The followings are the available model relations:
 * @property Budget $parent0
 * @property Budget[] $budgets
 * @property Enquiry[] $enquiries
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
			array('year, concept, initial_provision, actual_provision, trimester_1, trimester_2, trimester_3, trimester_4, featured', 'required'),
			array('parent, year, featured, weight', 'numerical', 'integerOnly'=>true),
			array('initial_provision, actual_provision, trimester_1, trimester_2, trimester_3, trimester_4', 'type', 'type'=>'float'),
			//array('initial_provision, actual_provision, t1, t2, t3, t4', 'length', 'max'=>14),
			array('code, csv_id, csv_parent_id', 'length', 'max'=>20),
			//array('csv_id', 'unique', 'className' => 'Budget'),	// this is a good idea but need to check against year
			array('label, concept', 'length', 'max'=>255),
			array('year', 'unique', 'className'=>'Budget', 'on'=>'newYear'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent, year, code, label, concept, provision, featured, weight', 'safe', 'on'=>'search'),
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
			'enquirys' => array(self::HAS_MANY, 'Enquiry', 'budget'),
		);
	}

	public function behaviors()  {
		// http://www.yiiframework.com/forum/index.php/topic/10285-how-to-compare-two-active-record-models/
		return array('PCompare'); 
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent' => __('Parent'),
			'csv_id' => 'internal code',
			'csv_parent_id' => 'internal parent code',
			'year' => __('Year'),
			'code' => __('Code'),
			'label' => __('Label'),
			'concept' => __('Concept'),
			'initial_provision' => __('Initial provision'),
			'actual_provision' => __('Actual provision'),
			'trimester_1' => __('Spent T1'),
			'trimester_2' => __('Spent T2'),
			'trimester_3' => __('Spent T3'),
			'trimester_4' => __('Spent T4'),
			'weight' => __('Weight'),
		);
	}

	public function getYearString()
	{
		return CHtml::encode($this->year).' - '.CHtml::encode($this->year +1);
	}


	private function getMySqlParams()
	{
		$result=array();
		$connectionString = Yii::app()->db->connectionString;
		// this expects $connectionString to be 'mysql:host=host;dbname=name'
		$connectionString = preg_replace('/^mysql:/', '', $connectionString);
		$params = explode(';', $connectionString);
		list($param, $result['host']) = explode('=', $params[0]);
		list($param, $result['dbname']) = explode('=', $params[1]);
		$result['user'] = Yii::app()->db->username;
		$result['pass'] = Yii::app()->db->password;
		return $result;
	}

	/**
	 * Dump the budget table
	 */
	public function dumpBudgets()
	{
		$timestamp = time();
		$fileName = 'budget-dump-'.date('Y-m-d-H-i-s',$timestamp).'.sql';

		$file = new File;
		$file->model = get_class($this);
		$file->uri = $file->baseDir.$file->model.'/'.$fileName;
		$file->name = __('Budget table saved on the').' '.date('Y/m/d H:i:s',$timestamp);

		if(!is_dir($file->baseDir.$file->model))	// should move this to model beforeSave.
			mkdir($file->baseDir.$file->model, 0700, true);

		$params = $this->getMySqlParams();
		$output = NULL;
		$return_var = NULL;
		$command = 'mysqldump --user='.$params['user'].' --password='.$params['pass'].' --host='.$params['host'].' '.$params['dbname'].' budget > '.$file->uri;
		exec($command, $output, $return_var);

		if(!$return_var){
			$file->save();
			echo 0;
		}else{
			if(file_exists($file->uri))
				unlink($file->uri);
			echo 1;
		}
	}

	/**
	 * Restore the budget table
	 */
	public function restoreBudgets($file_id)
	{
		$file = File::model()->findByPk($file_id);

		$params = $this->getMySqlParams();
		$output = NULL;
		$return_var = NULL;
		$command = 'mysql --user='.$params['user'].' --password='.$params['pass'].' --host='.$params['host'].' '.$params['dbname'].' < '.$file->uri;
		exec($command, $output, $return_var);
		echo $return_var;
	}


	public function publicSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->addCondition('parent is null and year = '.$this->year);
		$yearly_budget=$this->find($criteria);
		if(!$yearly_budget)
			return new CActiveDataProvider($this,array('data'=>array()));
		if(!Yii::app()->user->isAdmin()){
			if($yearly_budget->code != 1)	//not published
				return new CActiveDataProvider($this,array('data'=>array()));
		}
		if(!$this->code && !$this->concept)
			return new CActiveDataProvider($this,array('data'=>array()));

		$criteria=new CDbCriteria;
		$criteria->addCondition('parent is not null');	// dont show year budget

		$criteria->compare('year',$this->year);
		$criteria->compare('code',$this->code);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('initial_provision',$this->initial_provision);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function changeTypeSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$root_budgets=$this->findAllByAttributes(array('parent'=>Null));
		foreach($root_budgets as $budget){
			if($budget->code == 0)	// this year not published
				$criteria->addCondition('year != '.$budget->year);			
		}
		$criteria->addCondition('parent is not null');	// dont show year budget

		$criteria->compare('year',$this->year);
		$criteria->compare('code',$this->code);
		$criteria->compare('concept',$this->concept,true);

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
		$criteria->addCondition('parent is not null');	// dont show year budget

		$criteria->compare('id',$this->id);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('csv_id',$this->csv_id,true);
		$criteria->compare('csv_parent_id',$this->csv_parent_id,true);
		$criteria->compare('year',$this->year);
		$criteria->compare('code',$this->code);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('initial_provision',$this->initial_provision);
		$criteria->compare('featured',$this->featured);
		$criteria->compare('weight',$this->weight);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
