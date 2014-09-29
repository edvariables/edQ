<?php
global $db;
$DBSERVER = "localhost";
$DBNAME = "rsn_4d";
$DBUSER = "rsn_4d";
$DBPASSWORD = "Zb8dvhrTrUY9Kvyw";
$DBTYPE = "mysqli";
$DBPORT = "";
$db = db::get($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
?>