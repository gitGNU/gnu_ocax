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
			array('code', 'length', 'max'=>20),
			array('csv_id, csv_parent_id', 'length', 'max'=>100),
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
			'trimester_1' => __('Trimester 1'),
			'trimester_2' => __('Trimester 2'),
			'trimester_3' => __('Trimester 3'),
			'trimester_4' => __('Trimester 4'),
			'weight' => __('Weight'),
		);
	}

	public function getYearString()
	{
		return CHtml::encode($this->year);
		//return CHtml::encode($this->year).' - '.CHtml::encode($this->year +1);
	}

	public function getLabel()
	{
		if($description = BudgetDescLocal::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>Yii::app()->language)))
			return $description->label;
			
		if($description = BudgetDescCommon::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>Yii::app()->language)))
			return $description->label;

		if(!$this->label)
			return $this->label;

		return __('Budget');
	}

	public function getConcept()
	{
		if($description = BudgetDescLocal::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>Yii::app()->language)))
			return $description->concept;
			
		if($description = BudgetDescCommon::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>Yii::app()->language)))
			return $description->concept;

		return $this->concept;
	}

	public function getTitle()
	{
		$label='';
		$concept='';
		$lang=Yii::app()->language;
		if($description = BudgetDescLocal::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>$lang))){
			if($description->label)
				$label = $description->label.': ';
			if($description->concept)
				$concept = $description->concept;
			if($concept)
				return $label.$concept;
		}
		if($description = BudgetDescCommon::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>$lang))){
			if($description->label)
				$label = $description->label.': ';
			if($description->concept)
				$concept = $description->concept;				
			return $label.$concept;
		}
		return $this->concept;
	}

	public function getDescription()
	{
		$lang=Yii::app()->language;
		if($description = BudgetDescLocal::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>$lang))){
			if($description->description)
				return $description->description;
		}
		if($description = BudgetDescCommon::model()->findByAttributes(array('csv_id'=>$this->csv_id, 'language'=>$lang))){
			if($description->description)
				return $description->description;
		}
		return '';
	}

	// return the percentage of this budget from total
	public function getPercentage()
	{
		if($rootBudget = $this->findByAttributes(array('csv_id'=>substr($this->csv_id, 0, 1), 'year'=>$this->year)))
			return percentage($this->actual_provision, $rootBudget->actual_provision);
		return '--';
	}
	
	public function getExecuted()
	{
		return $this->trimester_1 + $this->trimester_2 + $this->trimester_3 + $this->trimester_4;
	}	

	public function getCategory()
	{
		if($budget = $this->findByAttributes(array('csv_id'=>substr($this->csv_id, 0, 3))))
			return $budget->getConcept();
		return '<span style="color:red">getCategory('.$this->csv_id.')</span>';
	}	

	public function getPopulation($year=Null)
	{
		if(!$year)
			$year=$this->year;
		return $this->findByAttributes(array('year'=>$year,'parent'=>Null))->initial_provision;
	}
	
	public function getChildBudgets()
	{
		if(!$this->budgets)
			return null;
			
		$criteria=new CDbCriteria;
		$criteria->addCondition('parent = '.$this->id.' and actual_provision != 0');
		return $this->findAll($criteria);
	}
	
	public function isPublished()
	{
		return $this->find(array('condition'=>'parent IS NULL AND code = 1 AND year = '.$this->year));
	}
	
	public function getPublicYears()
	{
		return $this->findAll(array('condition'=>'parent IS NULL AND code = 1','order'=>'year DESC'));
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
		$file->path = '/files/'.$file->model.'/'.$fileName;
		$file->name = __('Budget table saved on the').' '.date('d-m-Y H:i:s',$timestamp);

		if(!is_dir($file->baseDir.'/files/'.$file->model))	// should move this to model beforeSave.
			mkdir($file->baseDir.'/files/'.$file->model, 0700, true);

		$params = getMySqlParams();
		$output = NULL;
		$return_var = NULL;
		
		$command = 'mysqldump --user='.$params['user'].' --password=\''.$params['pass'].'\' --host='.$params['host'].' '.$params['dbname'].' budget > '.$file->getURI();
		exec($command, $output, $return_var);

		//echo '<p>file: '.$file->getURI().'</p>';
		//print_r($output);

		if(!$return_var){
			$file->save();
			echo 0;
		}else{
			if(file_exists($file->getURI()))
				unlink($file->getURI());
			echo $return_var;
		}
	}

	/**
	 * Restore the budget table
	 */
	public function restoreBudgets($file_id)
	{
		$file = File::model()->findByPk($file_id);

		$params = getMySqlParams();
		$output = NULL;
		$return_var = NULL;
		$command = 'mysql --user='.$params['user'].' --password='.$params['pass'].' --host='.$params['host'].' '.$params['dbname'].' < '.$file->getURI();
		exec($command, $output, $return_var);
		echo $return_var;
	}

	public function budgetsWithoutDescription()
	{
		($this->csv_id)? $csv_id='AND b.csv_id LIKE "%'.$this->csv_id.'%"' : $csv_id='';
		($this->code)? $code='AND b.code = "'.$this->code.'"' : $code='';
		($this->year)? $year='AND b.year = "'.$this->year.'"' : $year='';
		
		$sql =" SELECT
				b.csv_id AS csv_id,
				b.id AS id,
				b.year AS year,
				b.code AS code
				FROM budget AS b
				LEFT JOIN (
					SELECT dc.csv_id AS common_csv_id, dl.csv_id AS local_csv_id
					from budget_desc_common dc
					LEFT OUTER JOIN budget_desc_local dl ON dc.csv_id = dl.csv_id
					UNION
					SELECT dc.csv_id AS common_csv_id, dl.csv_id AS local_csv_id
					from budget_desc_common dc
					RIGHT OUTER JOIN budget_desc_local dl ON dc.csv_id = dl.csv_id
				) AS description ON b.csv_id = description.common_csv_id OR b.csv_id = description.local_csv_id
				WHERE description.common_csv_id IS NULL AND description.local_csv_id IS NULL AND parent IS NOT NULL
				$code $year $csv_id 
				ORDER BY b.csv_id, b.year";

		$cnt = "SELECT COUNT(*) FROM ($sql) subq";
		$count = Yii::app()->db->createCommand($cnt)->queryScalar();

		return new CSqlDataProvider($sql,array(	'totalItemCount'=>$count,
												'pagination'=>array('pageSize'=>10),
											));
	}

	public function publicSearch()
	{
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
			
		$lang = Yii::app()->language;
		if($this->code){
			$sql = "SELECT	`b`.`csv_id` AS `csv_id`,
				`b`.`id` AS `id`,
				`b`.`year` AS `year`,
				`b`.`code` AS `code`,
				`b`.`initial_provision` AS `initial_provision`,
				`b`.`actual_provision` AS `actual_provision`,
				`description`.`common_text` AS `common_text`,
				`description`.`common_concept` AS `common_concept`,
				`description`.`local_text` AS `local_text`,
				`description`.`local_concept` AS `local_concept`,				
				`b`.`year` AS `common_score`,
				`b`.`year` AS `local_score`,
				`b`.`year` AS score
				
				FROM `budget` AS `b`
				LEFT JOIN (
					SELECT	`dc`.`csv_id` AS `common_csv_id`,
							`dc`.`language` AS `common_language`,
							`dc`.`concept` AS `common_concept`,
							`dc`.`text` AS `common_text`,
							`dl`.`csv_id` AS `local_csv_id`,
							`dl`.`language` AS `local_language`,
							`dl`.`concept` AS `local_concept`,
							`dl`.`text` AS `local_text`
					FROM `budget_desc_common` `dc`
					LEFT OUTER JOIN `budget_desc_local` `dl` ON `dc`.`csv_id` = `dl`.`csv_id` AND `dc`.`language` = `dl`.`language`
					UNION
					SELECT	`dc`.`csv_id` AS `common_csv_id`,
							`dc`.`language` AS `common_language`,
							`dc`.`concept` AS `common_concept`,
							`dc`.`text` AS `common_text`,
							`dl`.`csv_id` AS `local_csv_id`,
							`dl`.`language` AS `local_language`,
							`dl`.`concept` AS `local_concept`,
							`dl`.`text` AS `local_text`
					FROM budget_desc_common dc
					RIGHT OUTER JOIN `budget_desc_local` `dl` ON `dc`.`csv_id` = `dl`.`csv_id` AND `dc`.`language` = `dl`.`language`
				) AS `description` ON `b`.`csv_id` = `description`.`common_csv_id` OR `b`.`csv_id` = `description`.`local_csv_id`
				WHERE
					`year` = '".$this->year."' AND `code` = '".$this->code."'
					AND (`description`.`common_language` = '$lang' OR description.local_language = '$lang')
					AND `b`.`parent` IS NOT NULL";

			
			$cnt = "SELECT COUNT(*) FROM ($sql) subq";
			$count = Yii::app()->db->createCommand($cnt)->queryScalar();

			return new CSqlDataProvider($sql,array(	'totalItemCount'=>$count,
													'pagination'=>array('pageSize'=>10),
												));
		}

        $text = $this->concept;
		$sql = "SELECT	`b`.`csv_id` AS `csv_id`,
				`b`.`id` AS `id`,
				`b`.`year` AS `year`,
				`b`.`code` AS `code`,
				`b`.`initial_provision` AS `initial_provision`,
				`b`.`actual_provision` AS `actual_provision`,
				`description`.`common_text` AS `common_text`,
				`description`.`common_concept` AS `common_concept`,
				`description`.`local_text` AS `local_text`,
				`description`.`local_concept` AS `local_concept`,				
				`description`.`common_score` AS common_score,
				`description`.`local_score` AS local_score,
				(`description`.`common_score` + (`description`.`local_score`+6 * LOG(`description`.`local_score`+1))) AS score

				FROM `budget` AS `b`
				LEFT JOIN (
					SELECT	`dc`.`csv_id` AS `common_csv_id`,
							`dc`.`language` AS `common_language`,
							`dc`.`concept` AS `common_concept`,
							`dc`.`text` AS `common_text`,
							MATCH (`dl`.`concept`, `dl`.`text`) AGAINST ('$text') AS local_score,
							MATCH (`dc`.`concept`, `dc`.`text`) AGAINST ('$text') AS common_score,
							`dl`.`csv_id` AS `local_csv_id`,
							`dl`.`language` AS `local_language`,
							`dl`.`concept` AS `local_concept`,
							`dl`.`text` AS `local_text`
					from budget_desc_common dc
					LEFT OUTER JOIN `budget_desc_local` `dl` ON 
									`dc`.`csv_id` = `dl`.`csv_id` AND
									`dc`.`language` = `dl`.`language`
					UNION
					SELECT	`dc`.`csv_id` AS `common_csv_id`,
							`dc`.`language` AS `common_language`,
							`dc`.`concept` AS `common_concept`,
							`dc`.`text` AS `common_text`,
							MATCH (`dl`.`concept`, `dl`.`text`) AGAINST ('$text') AS local_score,
							MATCH (`dc`.`concept`, `dc`.`text`) AGAINST ('$text') AS common_score,
							`dl`.`csv_id` AS `local_csv_id`,
							`dl`.`language` AS `local_language`,
							`dl`.`concept` AS `local_concept`,
							`dl`.`text` AS `local_text`
					FROM budget_desc_common dc
					RIGHT OUTER JOIN `budget_desc_local` `dl` ON `dc`.`csv_id` = `dl`.`csv_id` AND `dc`.`language` = `dl`.`language`
				) AS `description` ON 
						`b`.`csv_id` = `description`.`common_csv_id` OR
						`b`.`csv_id` = `description`.`local_csv_id`

				WHERE
					`year` = '".$this->year."' AND
					(`description`.`common_language` = '$lang' OR description.local_language = '$lang') AND
					(common_score > 0 OR local_score > 0)
				ORDER BY score DESC";


		$cnt = "SELECT COUNT(*) FROM ($sql) subq";
		$count = Yii::app()->db->createCommand($cnt)->queryScalar();

		return new CSqlDataProvider($sql,array(	'totalItemCount'=>$count,
												'pagination'=>array('pageSize'=>10),
											));
	}

	public function getAllBudgetsWithCSV_ID()
	{
		$criteria=new CDbCriteria;

		$root_budgets=$this->findAllByAttributes(array('parent'=>Null, 'code'=>0));	// code means published

		foreach($root_budgets as $budget){
			//if($budget->code == 0)	// this year not published
			$criteria->addCondition('year != '.$budget->year);
		}

		$criteria->addCondition('csv_id = "'.$this->csv_id.'"');
		$criteria->order = 'year DESC';
		return $this->findAll($criteria);	
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

	public function featuredSearch()
	{
		//$root_budgets=$this->findAllByAttributes(array('parent'=>Null));
		$criteria=new CDbCriteria;

		/*
		SELECT * FROM `budget` `t` LEFT JOIN budget as child
		ON t.id = child.parent AND t.year = child.year 
		WHERE (CHAR_LENGTH(t.csv_id) > 1) AND child.id IS NOT NULL group by t.id
		*/
		// we only show budgets that have children. otherwise the graphs don't make sense.
		$criteria->select = 't.*';
		$criteria->together = true;
		$criteria->join = 'LEFT JOIN budget as child ON t.id = child.parent AND t.year = child.year';
		$criteria->addCondition('child.id IS NOT NULL');
		$criteria->group = 't.id';

		$criteria->addCondition('CHAR_LENGTH(t.csv_id) > 1');	// don't show 'S' o 'I', etc
		
		$criteria->compare('t.featured', $this->featured);
		$criteria->compare('t.code', $this->code);
		$criteria->compare('t.concept', $this->concept, true);
		$criteria->compare('t.csv_id', $this->csv_id, true);
		$criteria->compare('t.year',$this->year);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'t.csv_id ASC'),
		));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
/*
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->addCondition('parent is not null');	// dont show year budget

		//$criteria->compare('id',$this->id);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('csv_id',$this->csv_id,true);
		$criteria->compare('csv_parent_id',$this->csv_parent_id,true);
		$criteria->compare('year',$this->year);
		$criteria->compare('code',$this->code);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('initial_provision',$this->initial_provision);
		$criteria->compare('featured',$this->featured);
		//$criteria->compare('weight',$this->weight);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
*/
}

