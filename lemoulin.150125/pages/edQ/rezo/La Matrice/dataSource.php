<?php
global $db;
$DBSERVER = "localhost";
$DBNAME = "lamatrice";
$DBUSER = "lamatrice";
$DBPASSWORD = "GhqQKnjxxhrhfvZC";
$DBTYPE = "mysqli";
$DBPORT = "";
$db = db::get($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
?>