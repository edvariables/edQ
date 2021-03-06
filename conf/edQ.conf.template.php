<?php
ini_set( "display_errors", 1);
error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');

define ("DBSERVER", "localhost");
define ("DBNAME", "edq");
define ("DBUSER", "ED");
define ("DBPASSWORD", "");
define ("DBTYPE", "mysqli");
define ("DBPORT", "");
define ("DBCHARSET", "UTF8");
define ("DBPERSIST", "FALSE"); /* FALSE|TRUE mysql only */

define ("TREE_ROOT", 1);
define ("TREE_ROOT_NAME", 'edQ'); 

define ("MYSQLDUMP", "D:\\Wamp\\bin\\mysql\\mysql5.6.17\\bin\\mysqldump.exe");

define ("LOGROOT", "D:\\Wamp\\www\\edQ\\logs");

global $PLUGINS;
$PLUGINS = array('jstree');
?>