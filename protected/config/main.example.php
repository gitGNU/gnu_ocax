<?php
/**
OCAX -- Citizen driven Observatory software
Copyright (C) 2015 OCAX Contributors. See AUTHORS.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/


// For help join our mailing list. http://ocax.net/cgi-bin/mailman/listinfo/lista

$config = array(
	'theme'=>'default',
	
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
		'ocaxnetwork'=>true,
	),

	// This default should be good. Only touch this if you've moved the base directories.
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',	
);
return array_merge_recursive($config, require_once(dirname(__FILE__).'/_config.php'));
