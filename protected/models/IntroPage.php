<?php

/**
 * This is the model class for table "intro_page".
 *
 * The followings are the available columns in table 'intro_page':
 * @property integer $id
 * @property integer $weight
 * @property integer $toppos
 * @property integer $leftpos
 * @property integer $width
 * @property integer $published
 *
 * The followings are the available model relations:
 * @property IntroPageContent[] $introPageContents
 */
class IntroPage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IntroPage the static model class
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
		return 'intro_page';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weight, toppos, leftpos, width, published', 'required'),
			array('weight, toppos, leftpos, width, published', 'numerical', 'integerOnly'=>true),
			array('weight', 'unique', 'className' => 'IntroPage'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('weight, toppos, leftpos, width, published', 'safe', 'on'=>'search'),
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
			'introPageContents' => array(self::HAS_MANY, 'IntroPageContent', 'page'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'weight' => __('Order'),
			'toppos' => __('Top'),
			'leftpos' => __('Left'),
			'width' => __('Width'),
			'published' => __('Published'),
		);
	}

	/**
	 * Return the Title of the first related content object
	 */
	public function getTitleForModel($id,$lang=null)
	{
		if(!$lang){
			$content=IntroPageContent::model()->find(array('condition'=> 'page = '.$id));
			if(!$content)
				return Null;
		}
		else{
			$content=IntroPageContent::model()->find(array('condition'=> 'page = '.$id.' and language = "'.$lang.'"'));
			if(!$content)
				return Null;
		}
		return $content->title;
	}

	public function getContent($lang)
	{
		return IntroPageContent::model()->find(array('condition'=> 'page = '.$this->id.' and language = "'.$lang.'"'));
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
		$criteria->compare('weight',$this->weight);
		$criteria->compare('toppos',$this->toppos);
		$criteria->compare('leftpos',$this->leftpos);
		$criteria->compare('width',$this->width);
		$criteria->compare('published',$this->published);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
