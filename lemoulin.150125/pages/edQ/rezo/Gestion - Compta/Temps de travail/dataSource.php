<?php
global $db;
$DBSERVER = "localhost";
$DBNAME = "fengoffice";
$DBUSER = "sortirdunucleair";
$DBPASSWORD = "rez12mysql0)";
$DBTYPE = "mysqli";
$DBPORT = "";
$db = db::get($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
?>