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

use Clouddueling\Mysqldump\Mysqldump; 
 
class Backup
{
	public $tables = array();
	public $fp ;
	public $file_name;
	public $_path = null;
	public $back_temp_file = 'db_backup_';

	public function init()
	{

	}

	public function dump()
	{
		$error='';
		$backupDir = Yii::app()->basePath.'/runtime/';
		foreach(glob($backupDir.'backup-*') as $f) {
			unlink($f);
		}
		foreach(glob($backupDir.'*-backup.sql') as $f) {
			unlink($f);
		}
		$baseDir = dirname(Yii::app()->request->scriptFile);
		$filesDir = $baseDir.'/files';
		$backupFileName = 'backup-'.date('d-m-Y-H-i-s').'.zip';

		$zip = new ZipArchive();
		if (!$zip->open($backupDir.$backupFileName, ZIPARCHIVE::CREATE))
			return array(null, null, __('Cannot create zip file'));

		$dump_file = $backupDir.date('d-m-Y-H-i-s').'-backup.sql';
		$params = getMySqlParams();
		if(Config::model()->findByPk('databaseDumpMethod') == 'native'){
			if($error = $this->dumpDatabase($dump_file, $params))
				return array(null, null, __('exec(mysqldump) returns:').$error);
		}elseif(Config::model()->findByPk('databaseDumpMethod') == 'alternative')
			$this->dumpDatabaseAlernative($dump_file, $params)
		else
			return array(null, null, __('No database dump method available'));
		

		$this->Zip($filesDir, $zip);
		$zip->addFile($dump_file, 'database.sql');
		$zip->addFile(Yii::app()->basePath.'/data/RESTORE','RESTORE');
		$zip->addFile(Yii::app()->basePath.'/data/ocax.version','VERSION');
		$zip->close();

		return array($backupDir, $backupFileName, $error);
	}

	public function dumpDatabase($filePath, $params)
	{
		
		
		$output = NULL;
		$return_var = NULL;
		$command = 'mysqldump --user='.$params['user'].' --password='.$params['pass'].' --host='.$params['host'].' '.$params['dbname'].' > '.$filePath;
		exec($command, $output, $return_var);

		if(!$return_var){
			return 0;
		}else{
			if(file_exists($filePath))
				unlink($filePath);
			$this->dumpDatabaseAlernative($filePath, $params);
			return $return_var;
		}
	}
	
	public function dumpDatabaseAlernative($filePath, $params)
	{
		Yii::import('application.extensions.Clouddueling.Mysqldump.*');
		require_once('Mysqldump.php');
		$dump =  new Mysqldump($params['dbname'], $params['user'], $params['pass'], $params['host']);
		$dump->start($filePath);
	}	
	
	// http://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php/1334949#1334949
	private function Zip($source, $zip)
	{
		//$source = str_replace('\\', '/', realpath($source));
		//$source = realpath($source);
		if (is_dir($source) === true){
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
			$zip->addEmptyDir('files/');
			
			foreach ($files as $file){
				$file = str_replace('\\', '/', $file);
				// Ignore "." and ".." folders
				if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
					continue;

				$file = realpath($file);

				if (is_dir($file) === true)
					$zip->addEmptyDir(str_replace($source . '/', 'files/', $file . '/'));
				else if (is_file($file) === true)
					$zip->addFromString(str_replace($source . '/', 'files/', $file), file_get_contents($file));
			}
		}
		else if (is_file($source) === true)
			$zip->addFromString(basename($source), file_get_contents($source));
	}
}
