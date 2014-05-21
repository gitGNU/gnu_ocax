<?php
/**
 *
 * Import sql
 *
 * @author davidhhuan
 * http://www.cnblogs.com/davidhhuan/archive/2011/12/30/2306841.html
 */
 
// also look at http://www.yiiframework.com/forum/index.php/topic/28947-execute-sql-file-in-migration/
 
function runSQLFile($file){
	$pdo = Yii::app()->db->pdoInstance;
	try 
	{ 
		if (file_exists($file)) {
			$sqlStream = file_get_contents($file);
			$sqlStream = rtrim($sqlStream);
			$newStream = preg_replace_callback("/\((.*)\)/", create_function('$matches', 'return str_replace(";"," $$$ ",$matches[0]);'), $sqlStream); 
			$sqlArray = explode(";", $newStream); 
			foreach ($sqlArray as $value) { 
				if (!empty($value)){
					$sql = str_replace(" $$$ ", ";", $value) . ";";
					$pdo->exec($sql);
				} 
			} 
			//echo "succeed to import the sql data!";
			return true;
		} 
	} 
	catch (PDOException $e) { 
		echo $e->getMessage();
		exit; 
	}
}
