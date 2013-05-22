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

		$header = 'internal code|internal parent code|code|initial provision|actual provision|trimester 1|trimester 2|trimester 3|trimester 4|label|concept'.PHP_EOL;

		$tmp_fn = '/tmp/csv-' . mt_rand(10000,99999);
		$fh = fopen($tmp_fn, 'w');
		fwrite($fh, $header);

		$budgets = Budget::model()->findAllBySql('	SELECT csv_id, csv_parent_id, code, label, concept, initial_provision, actual_provision,
													trimester_1, trimester_2, trimester_3, trimester_4
													FROM budget
													WHERE year = '.$year.' AND parent IS NOT NULL');
		foreach($budgets as $b){
			fwrite($fh, $b->csv_id.'|'.$b->csv_parent_id.'|'.$b->code.'|'.$b->initial_provision.'|'.$b->actual_provision.
						'|'.$b->trimester_1.'|'.$b->trimester_2.'|'.$b->trimester_3.'|'.$b->trimester_4.
						'|'.$b->label.'|'.$b->concept.PHP_EOL);
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
