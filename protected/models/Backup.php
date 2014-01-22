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

 
class Backup
{
	
	public function dump()
	{
		$baseDir = dirname(Yii::getPathOfAlias('application'));
		$backupDir = Yii::app()->basePath.'/runtime/';
		$filesDir = $baseDir.'/app/files';
		$error = Null;
		
		$backupFileName = 'backup-'.date('d-m-Y-H-i-s').'.zip';

		foreach(glob($backupDir.'backup-*') as $f) {
			unlink($f);
		}
		
		$zip = new ZipArchive();
		if (!$zip->open($backupDir.$backupFileName, ZIPARCHIVE::CREATE)) {
			$error = __('Cannot create zip file');
		}
		if(!$error)
			$this->Zip($filesDir, $zip);
		
		$dump_file = $backupDir.date('d-m-Y-H-i-s').'.sql';
		if(!$error && $error_msg = $this->dumpDatabase($dump_file)){
			$error = __('Cannot dump database');
			$zip->close();
		}
		$zip->addFile($dump_file, 'database.sql');
		$zip->addFile(Yii::app()->basePath.'/data/RESTORE','RESTORE');
		$zip->addFile(Yii::app()->basePath.'/data/ocax.version','VERSION');
		$zip->close();

		return array($backupDir, $backupFileName, $error);
	}

	public function dumpDatabase($filePath)
	{
		$params = getMySqlParams();
		$output = NULL;
		$return_var = NULL;
		$command = 'mysqldump --user='.$params['user'].' --password='.$params['pass'].' --host='.$params['host'].' '.$params['dbname'].' > '.$filePath;
		exec($command, $output, $return_var);

		if(!$return_var){
			return Null;
		}else{
			if(file_exists($filePath))
				unlink($filePath);
			return $return_var;
		}
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
