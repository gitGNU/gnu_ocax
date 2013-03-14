<?php

/**
 * This is the model class for table "emailtext".
 *
 * The followings are the available columns in table 'emailtext':
 * @property integer $state
 * @property string $body
 */
class Emailtext extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Emailtext the static model class
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
		return 'emailtext';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state, body', 'required'),
			array('state', 'numerical', 'integerOnly'=>true),
			array('body', 'validateBody'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('state, body', 'safe', 'on'=>'search'),
		);
	}

	public function validateBody($attribute,$params)
	{
		if($attribute == 'body'){
			if( strpos($this->body, '%link%') === false)
				$this->addError('body','Text must include %link%');
		}
	}

	public function getBody($consulta=Null)
	{
		if($consulta){
			$consulta_link = '<a href="'.Yii::app()->createAbsoluteUrl('consulta/view', array('id' => $consulta->id)).'">'.
			Yii::app()->createAbsoluteUrl('consulta/view', array('id' => $consulta->id)).'</a>';
		}else
			$consulta_link = '<a href="/link/to/the/consulta">/link/to/the/consulta</a>';

		$body = str_replace('%link%', $consulta_link, $this->body);
		if( strpos($body, '%name%') !== false ){
			if($consulta && $consulta->state==0)
				$body = str_replace('%name%', $consulta->user0->fullname, $body);
			elseif($this->state == 0)
				$body = str_replace('%name%', '&lt;User\'s fullname will go here&gt;', $body);
			else
				$body = str_replace('%name%', '', $body);
		}
		return $body;
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
			'state' => __('State'),
			'body' => __('Body'),
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

		$criteria->compare('state',$this->state);
		$criteria->compare('body',$this->body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
