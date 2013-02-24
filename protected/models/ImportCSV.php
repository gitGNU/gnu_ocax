<?php

class ImportCSV extends CFormModel
{
	public $year;
	public $path;
	public $csv;
	public $step = 1;


	public function init()
	{
		$this->path = dirname(Yii::getPathOfAlias('application')).'/upload/importCSV/';
	}

	/**
	* @return array validation rules for model attributes.
	*/
	public function rules()
	{
		return array(
			// ... more rules here
			//array('picture', 'length', 'max' => 255, 'tooLong' => '{attribute} is too long (max {max} chars).', 'on' => 'upload'),
			array('csv', 'file', 'types' => 'csv', 'maxSize' => 1024 * 1024 * 2, 'tooLarge' => 'Size should be less then 2MB !!!',),
			// ... more rules here
		);
	}

	public function attributeLabels()
	{
		return array(
			'csv' => 'CSV file ',
		);
	}


}
