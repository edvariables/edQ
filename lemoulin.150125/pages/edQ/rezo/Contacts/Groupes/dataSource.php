<?php
global $db;
$DBSERVER = "localhost";
$DBNAME = "gestionmail";
$DBUSER = "sortirdunucleair";
$DBPASSWORD = "rez12mysql0)";
$DBTYPE = "mysqli";
$DBPORT = "";
$db = db::get($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
?>