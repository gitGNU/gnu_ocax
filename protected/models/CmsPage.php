<?php

/**
 * This is the model class for table "cms_page".
 *
 * The followings are the available columns in table 'cms_page':
 * @property integer $id
 * @property integer $block
 * @property integer $weight
 * @property integer $published
 *
 * The followings are the available model relations:
 * @property CmsPageContent[] $cmsPageContents
 */
class CmsPage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CmsPage the static model class
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
		return 'cms_page';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('block, weight', 'required'),
			array('block, weight, published', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, block, weight, published', 'safe', 'on'=>'search'),
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
			'cmsPageContents' => array(self::HAS_MANY, 'CmsPageContent', 'page'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'block' => 'Block',
			'weight' => 'Weight',
			'published' => 'Published',
		);
	}

	public function isMenuItemHighlighted()
	{
		if(strcasecmp(Yii::app()->controller->id, 'cmsPage')!==0)
			return 0;
		$arr = explode('/',Yii::app()->request->getPathInfo());
		if(isset($arr[1]) && $requestedPage= CmsPage::model()->findByPk($arr[1])){
			if(	$requestedPage->block == $this->block)
				return 1;
		}
		return 0;
	}


	/**
	 * Return the Title of the first related content object
	 */
	public function getTitleForModel($id)
	{
		$content=CmsPageContent::model()->find(array('condition'=> 'page = '.$id.' AND pageTitle IS NOT NULL'));
		return $content->pageTitle;
	}

	public function getContentForModel($lang)
	{
		if($content=CmsPageContent::model()->findByAttributes(array('page'=>$this->id,'language'=>Yii::app()->language)))
			return $content;
		
		return CmsPageContent::model()->find(array('condition'=> 'page = '.$this->id.' AND pageTitle IS NOT NULL'));
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
		$criteria->compare('block',$this->block);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('published',$this->published);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
