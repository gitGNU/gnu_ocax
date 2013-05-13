<?php

/**
 * This is the model class for table "cms_page_content".
 *
 * The followings are the available columns in table 'cms_page_content':
 * @property integer $id
 * @property integer $page
 * @property string $language
 * @property string $pageURL
 * @property string $pageTitle
 * @property string $body
 * @property string $heading
 * @property string $metaTitle
 * @property string $metaDescription
 * @property string $metaKeywords
 *
 * The followings are the available model relations:
 * @property CmsPage $page0
 */
class CmsPageContent extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CmsPageContent the static model class
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
		return 'cms_page_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('page, language, pageURL, pageTitle', 'required'),
			//array('page', 'safe', 'on'=>'cms_page_create'),
			array('page', 'numerical', 'integerOnly'=>true),
			array('language', 'length', 'max'=>2),
			array('pageURL, pageTitle, heading, metaTitle, metaDescription, metaKeywords', 'length', 'max'=>255),
			array('body', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, page, language, pageURL, pageTitle, body, heading, metaTitle, metaDescription, metaKeywords', 'safe', 'on'=>'search'),
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
			'page0' => array(self::BELONGS_TO, 'CmsPage', 'page'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'page' => 'Page',
			'language' => 'Language',
			'pageURL' => 'Page URL',
			'pageTitle' => 'Page Title',
			'body' => 'Body',
			'heading' => 'Heading',
			'metaTitle' => 'Meta Title',
			'metaDescription' => 'Meta Description',
			'metaKeywords' => 'Meta Keywords',
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
		$criteria->compare('page',$this->page);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('pageURL',$this->pageURL,true);
		$criteria->compare('pageTitle',$this->pageTitle,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('heading',$this->heading,true);
		$criteria->compare('metaTitle',$this->metaTitle,true);
		$criteria->compare('metaDescription',$this->metaDescription,true);
		$criteria->compare('metaKeywords',$this->metaKeywords,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}