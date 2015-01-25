<?php
global $db;
$DBSERVER = "localhost";
$DBNAME = "rsdn_vtigercrm";
$DBUSER = "rsdn_vtigercrm";
$DBPASSWORD = "umHRd38ANsFfVM5s";
$DBTYPE = "mysqli";
$DBPORT = "";
$db = db::get($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
?>