<?php
ini_set( "display_errors", 1);
error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');

define ("DBSERVER", "localhost");
define ("DBNAME", "edq");
define ("DBUSER", "ED");
define ("DBPASSWORD", "");
define ("DBTYPE", "mysqli");
define ("DBPORT", "");

define ("TREE_ROOT", 1);
define ("TREE_ROOT_NAME", 'edQ'); 

define("MYSQLDUMP", "D:\\Wamp\\bin\\mysql\\mysql5.6.17\\bin\\mysqldump.exe");
?>