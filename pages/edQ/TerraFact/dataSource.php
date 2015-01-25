<?php
global $db;
$DBSERVER = "localhost";
$DBNAME = "terrafact";
$DBUSER = "ED";
$DBPASSWORD = "";
$DBTYPE = "mysqli";
$DBPORT = "";
if(!isset($arguments))
	echo($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
else
	$db = db::get($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
?>