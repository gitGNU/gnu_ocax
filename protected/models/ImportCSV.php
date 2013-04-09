<?php

class ImportCSV extends CFormModel
{
	public $year;
	public $path;
	public $csv;
	public $step = 1;


	public function init()
	{
		$this->path = dirname(Yii::getPathOfAlias('application')).'/app/files/csv/';
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
			'csv' => __('CSV file'),
		);
	}

	public function createCSV($year)
	{
		$file = new File;
		$file->name = $year.'.csv';
		$file->model = 'DatabaseDownload/data';
		$file->uri=$file->baseDir.$file->model.'/'.$file->name;
		$file->webPath=Yii::app()->request->baseUrl.'/files/'.$file->model.'/'.$file->name;
		if($existing_file = File::model()->findByAttributes(array('uri'=>$file->uri)))
			$file = $existing_file;

		$header = 'internal code|code|label|concept|initial provision|actual provision|spent t1|spent t2|spent t3|spent t4|internal parent code'.PHP_EOL;

		$tmp_fn = '/tmp/csv-' . mt_rand(10000,99999);
		$fh = fopen($tmp_fn, 'w');
		fwrite($fh, $header);

		$budgets = Budget::model()->findAllBySql('	SELECT csv_id, code, label, concept, initial_provision, actual_provision,
													spent_t1, spent_t2, spent_t3, spent_t4, csv_parent_id
													FROM budget
													WHERE year = '.$year.' AND parent IS NOT NULL');
		foreach($budgets as $b){
			fwrite($fh, $b->csv_id.'|'.$b->code.'|'.$b->label.'|'.$b->concept.'|'.$b->initial_provision.'|'.$b->actual_provision.
						'|'.$b->spent_t1.'|'.$b->spent_t2.'|'.$b->spent_t3.'|'.$b->spent_t4.'|'.$b->csv_parent_id. PHP_EOL);
		}
		fclose($fh);
		if (copy($tmp_fn, $file->uri)) {
			unlink($tmp_fn);
			$file->name = $year.'.csv'.' ('.__('generated on the').' '.date('Y-m-d H:i:s').')';
			$file->save();
			return array($file, $budgets);
		}else
			return Null;
	}

}
