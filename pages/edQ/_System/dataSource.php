<?php /* cf /conf/edQ.conf.php */
global $db;
$DBSERVER = DBSERVER;
$DBNAME = DBNAME;
$DBUSER = DBUSER;
$DBPASSWORD = DBPASSWORD;
$DBTYPE = DBTYPE;
$DBPORT = DBPORT;
$db = db::get($DBTYPE . '://' . $DBUSER . ':' . $DBPASSWORD . '@' . $DBSERVER . '/' . $DBNAME);
?>