<?php
// For help join our mailing list. http://ocax.net/cgi-bin/mailman/listinfo/lista

$config = array(
	// See the list of directory names in app/themes for possible options
	'theme'=>'khaki',
	
	// your database connection
	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=ocax',
			'emulatePrepare' => true,
			'username' => 'ocax',
			'password' => 'password',
			'charset' => 'utf8',
		),
	),
	// http://en.wikipedia.org/wiki/List_of_tz_database_time_zones
	'timeZone' => 'Europe/Madrid',
	
	// Do you want to be part of the ocax network? http://ocax.net/network/
	// We hope the network will provide support, automated backups and updates.
	'params'=>array(
		'ocaxnetwork'=>false,
		'databaseDumpMethod'=>'MySQLDump',	// MySQLDump(recommended) or FkMySQLDump
	),

	// This default should be good. Only touch this if you've moved the base directories.
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',	
);
return array_merge_recursive($config, require_once(dirname(__FILE__).'/_config.php'));
