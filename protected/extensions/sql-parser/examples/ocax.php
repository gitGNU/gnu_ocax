<?php

/**
 * you cannot execute this script within Eclipse PHP
 * because of the limited output buffer. Try to run it
 * directly within a shell.
 */

require_once dirname(__FILE__) . '/../src/PHPSQLParser.php';
require_once dirname(__FILE__) . '/../src/PHPSQLCreator.php';

$sql = "UPDATE config SET parameter='administrationName' WHERE parameter='councilName';
INSERT INTO config(parameter, value, required, description) VALUES ('administrationLatitude', '', '0', 'Administration\'s WGS84 latitude on earth');
INSERT INTO config(parameter, value, required, description) VALUES ('administrationLongitude', '', '0', 'Administration\'s WGS84 longitude on earth');";

$parser = new PHPSQLParser($sql, true);

$creator = new PHPSQLCreator();
$creator->create($parser->parsed);
	
echo $creator->created;
?>
