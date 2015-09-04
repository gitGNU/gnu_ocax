<?php

/**
 * This is the model class for table "archive".
 *
 * The followings are the available columns in table 'archive':
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property string $extension
 * @property integer $author
 * @property string $description
 * @property integer $container
 * @property string $created
 *
 * The followings are the available model relations:
 * @property User $author0
 */
class Archive extends CActiveRecord
{
	
	public $baseDir;
	public $archiveRoot = '/files/archive/';
	public $file;
	public $searchText=Null;

	public function init()
	{
		$this->baseDir = dirname(Yii::app()->request->scriptFile);
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
			array('name, path, author, description, created', 'required', 'on'=>'uploadFile'),
			array('name, path, author, description, created', 'required', 'on'=>'createContainer'),
			array('author, container', 'numerical', 'integerOnly'=>true),
			array('name, path', 'length', 'max'=>255),
			array('extension', 'length', 'max'=>5),
			// The following rule is used by search().
			array('searchText', 'safe', 'on'=>'search'),
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
			'container0' => array(self::BELONGS_TO, 'Archive', 'container'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'file' => __('File'),
			'name' => __('Name'),
			'path' => __('Path'),
			'extension' => 'Extension',
			'author' => __('Author'),
			'description' => __('Description'),
			'created' => __('Created'),
			'searchText' => __('Search'),
		);
	}

	protected function beforeDelete()
	{
		
		if (file_exists($this->getURI())){
			if ($this->is_container){
				rmdir($this->getURI());
			}else{
				unlink($this->getURI());
			}			
		}
		return parent::beforeDelete();
	}

	public function getURI()
	{
		return $this->baseDir.$this->path;
	}

	public function getURL()
	{
		if ($this->is_container){
			$path = str_replace($this->archiveRoot, '', $this->path);
			return Yii::app()->createAbsoluteUrl('').'/archive/index/'.$path;
		}
		return Yii::app()->createAbsoluteUrl('').'/archive/'.$this->id;
	}

	public function getParentContainerURL()
	{
		if ($this->container){
			$path = str_replace($this->archiveRoot, '', $this->container0->path);
		}else{
			$path = '';
		}
		return Yii::app()->createAbsoluteUrl('').'/archive/index/'.$path;
	}

	public function getWebPath()
	{
		if ($this->container){
			return str_replace($this->archiveRoot, '', $this->path);
		}
		return Yii::app()->request->baseUrl.$this->path;
	}

	public function getParentContainerWebPath()
 	{
		if ($this->container){
			$path = str_replace($this->archiveRoot, '', $this->container0->path);
		}else{
			$path = '';
		}
		return $path;
	}

	public function buildPathFromName()
	{
		$this->name = str_replace(array('\\','\/'), '', $this->name);
		if ($container = Archive::model()->findByPk($this->container)){
			$this->path = $container->path.'/'.strtolower(str_replace(' ', '-', trim(string2ascii($this->name))));
		}else{
			$this->path = $this->archiveRoot.strtolower(str_replace(' ', '-', trim(string2ascii($this->name))));
		}
	}

	public function getContainerFromPath($containerPath)
	{
		return $this->findByAttributes(array('path'=>$this->archiveRoot.$containerPath));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($container = Null)
	{
		$criteria=new CDbCriteria;
		$text = $this->searchText;
		
		$criteria->addCondition("name LIKE :match OR description LIKE :match");
		$criteria->params[':match'] = "%$text%";
		if ($container){
			$criteria->compare('container', $container->id);
		}else{
			$criteria->addCondition('container is NULL');
		}
		$criteria->order = 'is_container DESC, name ASC';
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>20),
		));
	}

	/*
	 * I'm building my own 'ButtonsColumn' because webfont code shows as the link's 'alt' property
	 * I don't want non proviliged users to see it.
	 */
	public function getGridActions($user_id, $is_admin){	
		$result = format_date($this->created).'&nbsp;&nbsp;';
		
		if ($this->author == $user_id || $is_admin){
			$canDelete = true;
			if ($this->is_container){
				if($this->findByAttributes(array('container'=>$this->id))){
					$canDelete = false;
				}
			}
			if ($canDelete){
				$result .= '<i class="icon-cancel-circled delete red" onClick="js:deleteArchive('.$this->id.')"></i>';
			}
		}
		return '<div style="white-space:nowrap; float:right;">'.$result.'</div>';
	}

	public function getExtension($file_name){
		return pathinfo($file_name, PATHINFO_EXTENSION);
	}
}
