<?php
ini_set( "display_errors", 1);
error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');

define ("DBSERVER", "localhost");
define ("DBNAME", "edq");
define ("DBUSER", "edq");
define ("DBPASSWORD", "fhB6rQ5bJEQVsaHP");
define ("DBTYPE", "mysqli");
define ("DBPORT", "");

define ("TREE_ROOT", 1);
define ("TREE_ROOT_NAME", 'edQ'); 

define("MYSQLDUMP", "/usr/bin/mysqldump");

define ("LOGROOT", "/var/www/lemoulin/logs");

global $PLUGINS;
$PLUGINS = array();
?>