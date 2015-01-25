<?php
global $db;
$DBSERVER = "localhost";
$DBNAME = "terrafact";
$DBUSER = "ED";
$DBPASSWORD = "";
$DBTYPE = "mysql";
$DBPORT = "";
$db = db::get($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
?>