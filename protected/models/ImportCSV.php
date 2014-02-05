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
	
	public function getHeader()
	{	
		return 'internal code|code|initial provision|actual provision|trimester 1|trimester 2|trimester 3|trimester 4|label|concept'.PHP_EOL;
	}

	public function getParentCode($internal_code)
	{	
		//echo '--'.$internal_code.'--<br />';
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

		//$original_encoding = mb_detect_encoding($content, 'UTF-8, iso-8859-1, iso-8859-15', true);
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
				if(!is_numeric($budget['initial_prov'])){
					$budget['initial_prov'] = $totals[$internal_code]['initial_prov'];
					$updated += 1;
				}
				if(!is_numeric($budget['actual_prov'])){
					$budget['actual_prov'] = $totals[$internal_code]['actual_prov'];
					$updated += 1;
				}
				if(!is_numeric($budget['t1'])){
					$budget['t1'] = $totals[$internal_code]['t1'];
					$updated += 1;
				}
				if(!is_numeric($budget['t2'])){
					$budget['t2'] = $totals[$internal_code]['t2'];
					$updated += 1;
				}
				if(!is_numeric($budget['t3'])){
					$budget['t3'] = $totals[$internal_code]['t3'];
					$updated += 1;
				}
				if(!is_numeric($budget['t4'])){
					$budget['t4'] = $totals[$internal_code]['t4'];
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

			if(!($budgets[$internal_code]['code'] && $budgets[$internal_code]['concept'])){
				if($description = BudgetDescription::model()->findByPk($lang.$internal_code)){
					if(!$budgets[$internal_code]['code'] && strlen($budgets[$internal_code]['csv_id']) > 3){
						$budgets[$internal_code]['code'] = $description->code;
						$updated += 1;
					}
					if(!$budgets[$internal_code]['concept']){
						$budgets[$internal_code]['concept'] = $description->concept;
						$updated += 1;	
					}
				}else
					if(!$budgets[$internal_code]['concept']){
						$budgets[$internal_code]['concept'] = 'UNKNOWN';
						$updated += 1;
					}
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

		$tmp_fn = '/tmp/csv-' . mt_rand(10000,99999);
		$fh = fopen($tmp_fn, 'w');
		fwrite($fh, $this->getHeader());

		$budgets = Budget::model()->findAllBySql('	SELECT csv_id, csv_parent_id, code, label, concept, initial_provision, actual_provision,
													trimester_1, trimester_2, trimester_3, trimester_4
													FROM budget
													WHERE year = '.$year.' AND parent IS NOT NULL');
		foreach($budgets as $b){
			fwrite($fh, $b->csv_id.'|'.$b->code.'|'.$b->initial_provision.'|'.$b->actual_provision.
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
