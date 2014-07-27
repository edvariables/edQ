<?php
global $db;
$DBSERVER = "localhost";
$DBNAME = "fengoffice";
$DBUSER = "fengoffice";
$DBPASSWORD = "52STcQH2DEm8BecH";
$DBTYPE = "mysqli";
$DBPORT = "";
$db = db::get($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
?>