<?php

/**
 * This is the model class for table "file".
 *
 * The followings are the available columns in table 'file':
 * @property integer $id
 * @property string $name
 * @property string $uri
 * @property string $webPath
 * @property string $model
 * @property integer $model_id
 */
class File extends CActiveRecord
{

	public $baseDir;
	public $file;

	public function init()
	{
		$this->baseDir = dirname(Yii::getPathOfAlias('application')).'/app/files/';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return File the static model class
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
		return 'file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uri, model', 'required'),
			array('model_id', 'numerical', 'integerOnly'=>true),
			array('name, uri, webPath', 'length', 'max'=>255),
			array('model', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, uri, webPath, model, model_id', 'safe', 'on'=>'search'),
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
		);
	}

	protected function beforeDelete()
	{
		unlink($this->uri);
		return parent::beforeDelete();
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => __('Name'),
			'uri' => 'Uri',
			'webPath' => 'Web Path',
			'model' => 'Model',
			'model_id' => 'Model',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('uri',$this->uri,true);
		$criteria->compare('webPath',$this->webPath,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('model_id',$this->model_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function normalize( $str )
	{
		$str = str_replace(' ', '-', $str);
	    # Quotes cleanup
		$str = ereg_replace( chr(ord("`")), "'", $str );		# `
		$str = ereg_replace( chr(ord("´")), "'", $str );		# ´
		$str = ereg_replace( chr(ord("„")), ",", $str );		# „
		$str = ereg_replace( chr(ord("`")), "'", $str );		# `
		$str = ereg_replace( chr(ord("´")), "'", $str );		# ´
		$str = ereg_replace( chr(ord("“")), "\"", $str );		# “
		$str = ereg_replace( chr(ord("”")), "\"", $str );		# ”
		$str = ereg_replace( chr(ord("´")), "'", $str );		# ´

		$unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
									'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
									'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
									'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
									'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
		$str = strtr( $str, $unwanted_array );

		# Bullets, dashes, and trademarks
		$str = ereg_replace( chr(149), "&#8226;", $str );	# bullet •
		$str = ereg_replace( chr(150), "&ndash;", $str );	# en dash
		$str = ereg_replace( chr(151), "&mdash;", $str );	# em dash
		$str = ereg_replace( chr(153), "&#8482;", $str );	# trademark
		$str = ereg_replace( chr(169), "&copy;", $str );	# copyright mark
		$str = ereg_replace( chr(174), "&reg;", $str );		# registration mark

		return $str;
	}

}
