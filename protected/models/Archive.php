<?php

/**
 * This is the model class for table "archive".
 *
 * The followings are the available columns in table 'archive':
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property string $mimeType
 * @property integer $author
 * @property string $description
 * @property string $created
 *
 * The followings are the available model relations:
 * @property User $author0
 */
class Archive extends CActiveRecord
{
	
	public $baseDir;
	public $file;

	public function init()
	{
		$this->baseDir = dirname(Yii::getPathOfAlias('application')).'/app';
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Archive the static model class
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
		return 'archive';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, path, author, description, created', 'required'),
			array('author', 'numerical', 'integerOnly'=>true),
			array('name, path', 'length', 'max'=>255),
			array('mimeType', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, path, mimeType, author, description, created', 'safe', 'on'=>'search'),
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
			'author0' => array(self::BELONGS_TO, 'User', 'author'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => __('Name'),
			'path' => __('Path'),
			'mimeType' => 'Mime-Type',
			'author' => __('Author'),
			'description' => __('Description'),
			'created' => __('Created'),
		);
	}

	protected function beforeDelete()
	{
		if(file_exists($this->getURI()))
			unlink($this->getURI());
		return parent::beforeDelete();
	}

	public function getURI()
	{
		return $this->baseDir.$this->path;
	}
	
	public function getWebPath()
	{
		return Yii::app()->request->baseUrl.$this->path;
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('mimeType',$this->mimeType,true);
		$criteria->compare('author',$this->author);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
