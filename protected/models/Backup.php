<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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
	public function backupSite()
	{
		$error='';
		$backupDir = Yii::app()->basePath.'/runtime/';
		if($glob = glob($backupDir.'backup-*')){
			foreach($glob as $f) {
				unlink($f);
			}
		}
		if($glob = glob($backupDir.'*-backup.sql')){
			foreach($glob as $f) {
				unlink($f);
			}
		}
		$baseDir = dirname(Yii::app()->request->scriptFile);
		$filesDir = $baseDir.'/files';
		$backupFileName = 'backup-'.date('d-m-Y-H-i-s').'.zip';

		$zip = new ZipArchive();
		if (!$zip->open($backupDir.$backupFileName, ZIPARCHIVE::CREATE))
			return array(null, null, __('Cannot create zip file'));

		$dump_file = $backupDir.date('d-m-Y-H-i-s').'-backup.sql';
		if($error = $this->dumpDatabase($dump_file)){
			if(file_exists($dump_file))
				unlink($dump_file);
		}
		$this->Zip($filesDir, $zip);
		$zip->addFile($dump_file, 'database.sql');
		$zip->addFile(Yii::app()->basePath.'/data/RESTORE','RESTORE');
		$zip->addFile(Yii::app()->basePath.'/data/ocax.version','VERSION');
		$zip->close();

		return array($backupDir, $backupFileName, $error);
	}

	public function dumpDatabase($filePath, $table=Null)
	{
		$params = getMySqlParams();
		$method = Config::model()->findByPk('databaseDumpMethod')->value;
		switch ($method) {
			case 'native':
				$output = NULL;
				$return_var = NULL;
				$command =	'mysqldump --user='.$params['user'].' --password='.$params['pass'].
							' --host='.$params['host'].' '.$params['dbname'].' '.$table.' > '.$filePath;
				exec($command, $output, $return_var);
				if($return_var)
					return 'exec(mysqldump) returned:'.$return_var;
				break;
			case 'alternative':
				Yii::import('application.extensions.Clouddueling.Mysqldump.*');
				require_once('Mysqldump.php');
				$dumpSettings = array();
				if($table)
					$dumpSettings = array('include-tables' => array($table));
				$dump =  new Mysqldump($params['dbname'], $params['user'], $params['pass'], $params['host'], 'mysql', $dumpSettings);
				$dump->start($filePath);
				// need to find a way to return errors here
				break;
			default:
				return '\''.$method.'\' is not a valid database dump method';
		}
		return 0;	// dumped ok.
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
