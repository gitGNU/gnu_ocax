<?php

/**
 * OCAX -- Citizen driven Observatory software
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
		$this->path = Yii::app()->basePath.'/runtime/tmp/csv/';
		if(!is_dir($this->path))
			createDirectory($this->path);
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

	public function getTmpCSVFilename($year=Null)
	{
		if(!$year)
			$year=$this->year;
		return $year.'-'.Yii::app()->user->id.'.csv';
	}

	public function getHeader()
	{
		return 'internal code|code|initial provision|actual provision|trimester 1|trimester 2|trimester 3|trimester 4|label|concept'.PHP_EOL;
	}

	public function getParentCode($internal_code)
	{
		if($isChild = strrpos($internal_code, "-"))
			return substr($internal_code, 0, $isChild);
		else
			return Null;
	}

	public function register2array($register)
	{
		list($id, $code, $initial_prov, $actual_prov, $t1, $t2, $t3, $t4, $label, $concept) = explode("|", $register);
		return array(
				'csv_id'=>$id,
				'code'=>$code,
				'initial_prov'=>$initial_prov,
				'actual_prov'=>$actual_prov,
				't1'=>$t1,
				't2'=>$t2,
				't3'=>$t3,
				't4'=>$t4,
				'label'=>$label,
				'concept'=>trim($concept),
				);
	}

	public function createEmptyBudgetArray()
	{
		return array(
					'internal_code'=>Null,
					'code'=>Null,
					'initial_prov' => Null,
					'actual_prov' => Null,
					't1' => Null,
					't2' => Null,
					't3' => Null,
					't4' => Null,
					'label' => Null,
					'concept' => Null,
				);
	}

	public function array2register($b)
	{
		return 	$b['csv_id'].'|'.$b['code'].'|'.$b['initial_prov'].'|'.$b['actual_prov'].
				'|'.$b['t1'].'|'.$b['t2'].'|'.$b['t3'].'|'.$b['t4'].
				'|'.$b['label'].'|'.$b['concept'];
	}

	public function csv2array()
	{
		$result= array();
		$lines = file($this->csv);

		foreach ($lines as $line_num => $line) {
			if($line_num==0)
				continue;
			list($csv_id, $code, $initial_prov, $actual_prov, $t1, $t2, $t3, $t4, $label, $concept) = explode("|", $line);
			$result[$csv_id]=$line;

		}
		return $result;
	}

	// convert csv to UTF-8
	public function checkEncoding()
	{
		$content = file_get_contents($this->csv);
		$original_encoding = mb_detect_encoding($content, 'UTF-8', true);
		if($original_encoding != 'UTF-8')
			return 0;
		else
			return 1;
	}


	public function orderCSV(){
		$ordered = $this->csv2array($this->csv);
		ksort($ordered);

		$fh = fopen($this->csv, 'w');
		fwrite($fh, $this->getHeader());
		foreach($ordered as $line){
			$line = preg_replace("/\s*[|]\s*/", "|", $line);
			$line = trim($line).PHP_EOL;
			fwrite($fh, $line);
		}
		fclose($fh);
	}

	protected function __addMissignRegisters(& $registers)
	{
		$cnt = 0;
		foreach($registers as $internal_code => $register){
			if($parent_id = $this->getParentCode($internal_code)){

				if(!array_key_exists($parent_id, $registers)){
					$cnt +=1;
					$newRegister = $this->createEmptyBudgetArray();
					$newRegister['internal_code'] = $parent_id;
					$reg = implode ( '|' , $newRegister );
					$registers[$parent_id]=$reg.PHP_EOL;
				}
			}
		}
		return $cnt;

	}

	public function addMissignRegisters()
	{
		$registers = $this->csv2array();
		$newRegisterCnt = 0;
		$wild_loop = 0;
		$cnt=0;

		while($cnt = $this->__addMissignRegisters($registers)){
			$newRegisterCnt += $cnt;
			$wild_loop += 1;
			if($wild_loop == 2000)
				break;
			reset($registers);
		}

		if($newRegisterCnt){
			ksort($registers);
			$fh = fopen($this->csv, 'w');
			fwrite($fh, $this->getHeader());
			foreach($registers as $line)
				fwrite($fh, $line);
			fclose($fh);
		}
		return $newRegisterCnt;
	}

	public function checkInternalCodeSanity()
	{
		$msg=array();;
		$registers = $this->csv2array();
		foreach($registers as $csv_id => $register){
			if(strlen($csv_id) > 3){	// because 'S-E' == 3
				$codes = explode("-", $csv_id);
				$pos=2;	// start at first code number S-E- =>1<= -11-111
				while($pos <= count($codes)){
					if(!isset($codes[$pos+1]))	// we're at the end of the array
						break;
					if(strpos($codes[$pos+1], $codes[$pos], 0) !== 0){
						$msg[] = '<br />Check internal_code '.$csv_id;
						break;
					}
					$pos += 1;
				}
			}
		}
		return $msg;
	}

	public function addMissingTotals()
	{
		$registers = $this->csv2array();
		$registers = array_reverse($registers, true);

		$budgets = array();
		foreach($registers as $internal_code => $register){
			$budgets[$internal_code] = $this->register2array($register);
		}
		$total=0;
		$parentID_placeholder=Null;
		$budgetID_parent=Null;
		$totals = array();
		$updated=0;
		
		foreach($budgets as $internal_code => & $budget){
			if(isset($totals[$internal_code])){
				if($budget['initial_prov'] === '0' || !is_numeric($budget['initial_prov'])){
					$budget['initial_prov'] = $totals[$internal_code]['initial_prov'];
					if($totals[$internal_code]['initial_prov'] !== 0)
						$updated += 1;
				}
				if($budget['actual_prov'] === '0' || !is_numeric($budget['actual_prov'])){
					$budget['actual_prov'] = $totals[$internal_code]['actual_prov'];
					if($totals[$internal_code]['actual_prov'] !== 0)
						$updated += 1;
				}
				if($budget['t1'] === '0' || !is_numeric($budget['t1'])){
					$budget['t1'] = $totals[$internal_code]['t1'];
					if($totals[$internal_code]['t1'] !== 0)
						$updated += 1;
				}
				if($budget['t2'] === '0' || !is_numeric($budget['t2'])){
					$budget['t2'] = $totals[$internal_code]['t2'];
					if($totals[$internal_code]['t2'] !== 0)
						$updated += 1;
				}
				if($budget['t3'] === '0' || !is_numeric($budget['t3'])){
					$budget['t3'] = $totals[$internal_code]['t3'];
					if($totals[$internal_code]['t3'] !== 0)
						$updated += 1;
				}
				if($budget['t4'] === '0' || !is_numeric($budget['t4'])){
					$budget['t4'] = $totals[$internal_code]['t4'];
					if($totals[$internal_code]['t4'] !== 0)
						$updated += 1;
				}
			}
			$budgetID_parent = $this->getParentCode($internal_code);

			if($budgetID_parent != $parentID_placeholder){
					if(!isset($totals[$budgetID_parent]))
						$totals[$budgetID_parent]=$this->createEmptyBudgetArray();
					$parentID_placeholder=$budgetID_parent;
			}
			$totals[$budgetID_parent]['initial_prov'] += $budget['initial_prov'];
			$totals[$budgetID_parent]['actual_prov'] += $budget['actual_prov'];
			$totals[$budgetID_parent]['t1'] += $budget['t1'];
			$totals[$budgetID_parent]['t2'] += $budget['t2'];
			$totals[$budgetID_parent]['t3'] += $budget['t3'];
			$totals[$budgetID_parent]['t4'] += $budget['t4'];
		}
		$budgets = array_reverse($budgets, true);

		if($updated){
			$fh = fopen($this->csv, 'w');
			fwrite($fh, $this->getHeader());
			foreach($budgets as $budget){
				$line = $this->array2register($budget);
				fwrite($fh, $line.PHP_EOL);
			}
			fclose($fh);
		}
		return $updated;
	}

	public function addMissingConcepts()
	{
		$registers = $this->csv2array();
		$budgets = array();
		$lang=getDefaultLanguage();
		$updated=0;
		
		foreach($registers as $internal_code => $register){
			$budgets[$internal_code] = $this->register2array($register);

			if(($budgets[$internal_code]['code'] === '') || ($budgets[$internal_code]['concept'] === '')){
				//echo '<p>'.$internal_code.' -'.$budgets[$internal_code]['code'].'- '.$budgets[$internal_code]['concept'].'<br />';
				if($description = BudgetDescCommon::model()->findByPk($lang.$internal_code)){
					if(($budgets[$internal_code]['code'] === '') && strlen($budgets[$internal_code]['csv_id']) > 3){
						//echo $budgets[$internal_code]['csv_id'].' -'.strlen($budgets[$internal_code]['csv_id']).'- is grt 3 1st<br />';
						$budgets[$internal_code]['code'] = $description->code;
						if($budgets[$internal_code]['code'] !== '')
							$updated++;
					}
					if(!$budgets[$internal_code]['concept']){
						$budgets[$internal_code]['concept'] = $description->concept;
						if($budgets[$internal_code]['concept'] !== '')
							$updated++;
					}
				}
				if($budgets[$internal_code]['concept'] === ''){
					$budgets[$internal_code]['concept'] = 'UNKNOWN';
					$updated++;
				}
				if(($budgets[$internal_code]['code'] === '') && strlen($budgets[$internal_code]['csv_id']) > 3){
					//echo $budgets[$internal_code]['csv_id'].' -'.strlen($budgets[$internal_code]['csv_id']).'- is grt 3 2nd<br />';
					if($isChild = strrpos($internal_code, "-"))
						$budgets[$internal_code]['code'] = substr($internal_code, $isChild+1);
					else
						$budgets[$internal_code]['code'] = 'UNKNOWN';
					$updated++;
				}
				//echo $internal_code.' -'.$budgets[$internal_code]['code'].'- '.$budgets[$internal_code]['concept'].'</p>';
			}
		}
		if($updated){
			$fh = fopen($this->csv, 'w');
			fwrite($fh, $this->getHeader());
			foreach($budgets as $budget){
				$line = $this->array2register($budget);
				fwrite($fh, $line.PHP_EOL);
			}
			fclose($fh);
		}
		return $updated;
	}

	public function createCSV($year)
	{
		$file = new File;
		$file->name = $year.'.csv';
		$file->model = 'DatabaseDownload/data';
		$file->path='/files/'.$file->model.'/'.$file->name;
		if($existing_file = File::model()->findByAttributes(array('path'=>$file->path)))
			$file = $existing_file;

		$budgets = Budget::model()->findAllBySql('	SELECT csv_id, code, label, concept, initial_provision, actual_provision,
													trimester_1, trimester_2, trimester_3, trimester_4
													FROM budget
													WHERE year = '.$year.' AND parent IS NOT NULL');
		$csv = array();
		foreach($budgets as $b)
			$csv[$b->csv_id] = $b->csv_id.'|'.$b->code.'|'.$b->initial_provision.'|'.$b->actual_provision.
						'|'.$b->trimester_1.'|'.$b->trimester_2.'|'.$b->trimester_3.'|'.$b->trimester_4.
						'|'.$b->label.'|'.$b->concept.PHP_EOL;

		ksort($csv);
		$tmpDir = Yii::app()->basePath.'/runtime/tmp/';
		$tmp_fn = $tmpDir.'csv-'.mt_rand(10000,99999);
		$fh = fopen($tmp_fn, 'w');
		fwrite($fh, $this->getHeader());
		foreach($csv as $line)
			fwrite($fh, $line);
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
