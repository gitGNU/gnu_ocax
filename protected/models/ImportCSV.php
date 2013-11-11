<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

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
		$file->path='/files/'.$file->model.'/'.$file->name;
		if($existing_file = File::model()->findByAttributes(array('path'=>$file->path)))
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
		
		
		$content = file_get_contents($tmp_fn);
		//file_put_contents($tmp_fn, "\xEF\xBB\xBF".  $content);
		

		$fh = fopen($tmp_fn, 'w');
        # Now UTF-8 - Add byte order mark 
        fwrite($fh, pack("CCC",0xef,0xbb,0xbf)); 
        fwrite($fh,$content); 
        fclose($fh); 

		
		if (copy($tmp_fn, $file->getURI())) {
			unlink($tmp_fn);
			$file->name = $year.'.csv'.' ('.__('generated on the').' '.date('d-m-Y H:i:s').')';
			$file->save();
			return array($file, $budgets);
		}else
			return Null;
	}

}
