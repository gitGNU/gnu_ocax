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

/*
 * https://github.com/kennberg/php-mysql-migrate
 */

Yii::import('application.includes.*');
require_once('runSQL.php');

class MigrateSchema{
	protected $MIGRATIONS_DIR;
	protected $MIGRATE_VERSION_FILE;
	protected $link = Null;
	protected $version = 0;
	protected $MIGRATE_FILE_PREFIX = 'migrate-version-';
	protected $MIGRATE_FILE_POSTFIX = '.sql';

    public function __construct( /*...*/ ) {
    	$this->MIGRATIONS_DIR = Yii::app()->basePath.'/migrations/';
    	$this->MIGRATE_VERSION_FILE = Yii::app()->basePath.'/runtime/mysql.migrate.version';
		if ($f = @fopen($this->MIGRATE_VERSION_FILE, 'r')) {
			$this->version = intval(fgets($f));
			fclose($f);
		}
    }

	public function add($fn)
	{
		$new_version = $this->version;
		// Check the new version against existing migrations.
		$files = get_migrations();
		$last_file = end($files);
		if ($last_file !== false) {
			$file_version = get_version_from_file($last_file);
			if ($file_version > $new_version)
				$new_version = $file_version;
		}
		// Create migration file path.
		$new_version++;
		$path = $this->MIGRATIONS_DIR.$this->MIGRATE_FILE_PREFIX.sprintf('%04d', $new_version).$this->MIGRATE_FILE_POSTFIX;

		echo "Adding a new migration script: $path\n";
		rename($fn, $path);
	}

	public function migrate()
	{
		// Find the latest version or start at 0.
		echo "Current database version is: $this->version\n";

		$files = get_migrations();

		// Check to make sure there are no conflicts such as 2 files under the same version.
		$errors = array();
		$last_file = false;
		$last_version = false;
		foreach ($files as $file) {
			$file_version = $this->get_version_from_file($file);
			if ($last_version !== false && $last_version === $file_version) {
				$errors[] = "$last_file --- $file";
			}
			$last_version = $file_version;
			$last_file = $file;
		}
		if (count($errors) > 0) {
			echo "Error: You have multiple files using the same version. " .
				"To resolve, move some of the files up so each one gets a unique version.\n";
			foreach ($errors as $error) {
				echo "  $error\n";
			}
			exit;
		}
		// Run all the new files.
		$this->connect();
		$found_new = false;
		foreach ($files as $file) {
			$file_version = $this->get_version_from_file($file);
			if ($file_version <= $this->version) {
				continue;
			}

			echo "Running: $file\n";
			$result = runSQLFile($this->MIGRATIONS_DIR.$file);
			if(!$result){
				echo $total-$success." queries failed\n";
				break;
			}
			echo "Done.\n";

			$this->version = $file_version;
			$found_new = true;

			// Output the new version number.
			$f = @fopen($this->MIGRATE_VERSION_FILE, 'w');
			if ($f) {
				fputs($f, $this->version);
				fclose($f);
			}else{
				echo "Failed to output new version to " . $this->MIGRATION_VERSION_FILE . "\n";
			}
		}
		
		if ($found_new)
			echo "Migration complete.\n";
		else
			echo "Your database is up-to-date.\n";
	}

	protected function get_migrations() {
		// Find all the migration files in the directory and return the sorted.
		$files = array();
		$dir = opendir($this->MIGRATIONS_DIR);
		while ($file = readdir($dir)) {
		if (substr($file, 0, strlen($this->MIGRATE_FILE_PREFIX)) == $this->MIGRATE_FILE_PREFIX) {
			$files[] = $file;
			}
		}
		asort($files);
		return $files;
	}

	protected function get_version_from_file($file) {
		return intval(substr($file, strlen($this->MIGRATE_FILE_PREFIX)));
	}
}
?>
