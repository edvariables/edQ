<?php
global $db;
$DBSERVER = "localhost";
$DBNAME = "edq";
$DBUSER = "ED";
$DBPASSWORD = "";
$DBTYPE = "mysqli";
$DBPORT = "";
$db = db::get($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
?>